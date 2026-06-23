<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\Bidang;
use App\Models\Boks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = SuratKeluar::with(['bidang', 'boks', 'user']);

        // Role restriction
        if (!$user->isAdmin()) {
            $query->where('bidang_id', $user->bidang_id);
        } else {
            // Admin can filter by bidang
            if ($request->filled('bidang_id')) {
                $query->where('bidang_id', $request->bidang_id);
            }
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%");
            });
        }

        // Boks filter
        if ($request->filled('boks_id')) {
            $query->where('boks_id', $request->boks_id);
        }

        // Date range filter
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_surat', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_surat', '<=', $request->tanggal_sampai);
        }

        $suratKeluars = $query->latest()->paginate(10)->withQueryString();
        
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        $boks = Boks::orderBy('nomor_boks')->get();

        return view('surat_keluar.index', compact('suratKeluars', 'bidangs', 'boks'));
    }

    public function create()
    {
        $user = auth()->user();
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        $boks = Boks::orderBy('nomor_boks')->get();
        return view('surat_keluar.create', compact('bidangs', 'boks'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'nomor_surat' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
            'tujuan' => ['required', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'boks_id' => ['required', 'exists:boks,id'],
            'scan_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];

        if ($user->isAdmin()) {
            $rules['bidang_id'] = ['required', 'exists:bidang,id'];
        }

        $request->validate($rules);

        $data = $request->except('scan_file');
        $data['user_id'] = $user->id;

        if (!$user->isAdmin()) {
            $data['bidang_id'] = $user->bidang_id;
        }

        if ($request->hasFile('scan_file')) {
            $file = $request->file('scan_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/surat_keluar'), $filename);
            $data['scan_file'] = 'uploads/surat_keluar/' . $filename;
        }

        SuratKeluar::create($data);

        return redirect()->route('surat-keluar.index')->with('success', 'Surat Keluar berhasil ditambahkan.');
    }

    public function show(SuratKeluar $suratKeluar)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $suratKeluar->bidang_id !== $user->bidang_id) {
            abort(403, 'Anda tidak memiliki akses ke surat ini.');
        }

        return view('surat_keluar.show', compact('suratKeluar'));
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $suratKeluar->bidang_id !== $user->bidang_id) {
            abort(403, 'Anda tidak memiliki akses ke surat ini.');
        }

        $bidangs = Bidang::orderBy('nama_bidang')->get();
        $boks = Boks::orderBy('nomor_boks')->get();
        return view('surat_keluar.edit', compact('suratKeluar', 'bidangs', 'boks'));
    }

    public function update(Request $request, SuratKeluar $suratKeluar)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $suratKeluar->bidang_id !== $user->bidang_id) {
            abort(403, 'Anda tidak memiliki akses ke surat ini.');
        }

        $rules = [
            'nomor_surat' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
            'tujuan' => ['required', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'boks_id' => ['required', 'exists:boks,id'],
            'scan_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];

        if ($user->isAdmin()) {
            $rules['bidang_id'] = ['required', 'exists:bidang,id'];
        }

        $request->validate($rules);

        $data = $request->except('scan_file');

        if ($user->isAdmin()) {
            $data['bidang_id'] = $request->bidang_id;
        }

        if ($request->hasFile('scan_file')) {
            // Delete old file
            if ($suratKeluar->scan_file && File::exists(public_path($suratKeluar->scan_file))) {
                File::delete(public_path($suratKeluar->scan_file));
            }

            $file = $request->file('scan_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/surat_keluar'), $filename);
            $data['scan_file'] = 'uploads/surat_keluar/' . $filename;
        }

        $suratKeluar->update($data);

        return redirect()->route('surat-keluar.index')->with('success', 'Surat Keluar berhasil diperbarui.');
    }

    public function destroy(SuratKeluar $suratKeluar)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $suratKeluar->bidang_id !== $user->bidang_id) {
            abort(403, 'Anda tidak memiliki akses ke surat ini.');
        }

        // Delete file
        if ($suratKeluar->scan_file && File::exists(public_path($suratKeluar->scan_file))) {
            File::delete(public_path($suratKeluar->scan_file));
        }

        $suratKeluar->delete();

        return redirect()->route('surat-keluar.index')->with('success', 'Surat Keluar berhasil dihapus.');
    }
}
