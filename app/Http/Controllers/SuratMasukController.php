<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\Bidang;
use App\Models\Boks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = SuratMasuk::with(['bidang', 'boks', 'user']);

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
                  ->orWhere('pengirim', 'like', "%{$search}%");
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

        $suratMasuks = $query->latest()->paginate(10)->withQueryString();
        
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        $boks = Boks::orderBy('nomor_boks')->get();

        return view('surat_masuk.index', compact('suratMasuks', 'bidangs', 'boks'));
    }

    public function create()
    {
        $user = auth()->user();
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        $boks = Boks::orderBy('nomor_boks')->get();
        return view('surat_masuk.create', compact('bidangs', 'boks'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'nomor_surat' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_terima' => ['required', 'date'],
            'pengirim' => ['required', 'string', 'max:255'],
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
            $file->move(public_path('uploads/surat_masuk'), $filename);
            $data['scan_file'] = 'uploads/surat_masuk/' . $filename;
        }

        SuratMasuk::create($data);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat Masuk berhasil ditambahkan.');
    }

    public function show(SuratMasuk $suratMasuk)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $suratMasuk->bidang_id !== $user->bidang_id) {
            abort(403, 'Anda tidak memiliki akses ke surat ini.');
        }

        return view('surat_masuk.show', compact('suratMasuk'));
    }

    public function edit(SuratMasuk $suratMasuk)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $suratMasuk->bidang_id !== $user->bidang_id) {
            abort(403, 'Anda tidak memiliki akses ke surat ini.');
        }

        $bidangs = Bidang::orderBy('nama_bidang')->get();
        $boks = Boks::orderBy('nomor_boks')->get();
        return view('surat_masuk.edit', compact('suratMasuk', 'bidangs', 'boks'));
    }

    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $suratMasuk->bidang_id !== $user->bidang_id) {
            abort(403, 'Anda tidak memiliki akses ke surat ini.');
        }

        $rules = [
            'nomor_surat' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_terima' => ['required', 'date'],
            'pengirim' => ['required', 'string', 'max:255'],
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
            if ($suratMasuk->scan_file && File::exists(public_path($suratMasuk->scan_file))) {
                File::delete(public_path($suratMasuk->scan_file));
            }

            $file = $request->file('scan_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/surat_masuk'), $filename);
            $data['scan_file'] = 'uploads/surat_masuk/' . $filename;
        }

        $suratMasuk->update($data);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat Masuk berhasil diperbarui.');
    }

    public function destroy(SuratMasuk $suratMasuk)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $suratMasuk->bidang_id !== $user->bidang_id) {
            abort(403, 'Anda tidak memiliki akses ke surat ini.');
        }

        // Delete file
        if ($suratMasuk->scan_file && File::exists(public_path($suratMasuk->scan_file))) {
            File::delete(public_path($suratMasuk->scan_file));
        }

        $suratMasuk->delete();

        return redirect()->route('surat-masuk.index')->with('success', 'Surat Masuk berhasil dihapus.');
    }
}
