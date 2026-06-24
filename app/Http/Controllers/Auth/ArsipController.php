<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ArsipController extends Controller
{
    /**
     * Daftar arsip dengan filter & pencarian.
     */
    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = Arsip::with(['bidang', 'user']);

        // Pembatasan akses per bidang untuk operator
        if (!$user->isAdmin()) {
            $query->where('bidang_id', $user->bidang_id);
        } else {
            if ($request->filled('bidang_id')) {
                $query->where('bidang_id', $request->bidang_id);
            }
        }

        // Filter pencarian teks
        if ($request->filled('search')) {
            $query->cari($request->search);
        }

        // Filter kode klasifikasi
        if ($request->filled('kode_klasifikasi')) {
            $query->where('kode_klasifikasi', 'like', $request->kode_klasifikasi . '%');
        }

        // Filter lokasi boks
        if ($request->filled('no_boks')) {
            $query->where('no_boks', $request->no_boks);
        }

        // Filter status retensi
        if ($request->filled('status_retensi')) {
            match ($request->status_retensi) {
                'aktif'   => $query->aktif(),
                'inaktif' => $query->inaktif(),
                default   => null,
            };
        }

        // Filter klasifikasi keamanan
        if ($request->filled('keamanan')) {
            $query->keamanan($request->keamanan);
        }

        // Filter rentang tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_diarsipkan', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_diarsipkan', '<=', $request->tanggal_sampai);
        }

        $arsips  = $query->latest()->paginate(15)->withQueryString();
        $bidangs = Bidang::orderBy('nama_bidang')->get();

        // Ambil daftar no_boks unik untuk filter dropdown
        $daftarBoks = Arsip::select('no_boks')
            ->whereNotNull('no_boks')
            ->when(!$user->isAdmin(), fn ($q) => $q->where('bidang_id', $user->bidang_id))
            ->distinct()
            ->orderBy('no_boks')
            ->pluck('no_boks');

        return view('arsip.index', compact('arsips', 'bidangs', 'daftarBoks'));
    }

    /**
     * Form tambah arsip baru.
     */
    public function create()
    {
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        return view('arsip.create', compact('bidangs'));
    }

    /**
     * Simpan arsip baru.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = $this->validationRules($user->isAdmin());
        $request->validate($rules);

        $data             = $request->except('scan_file');
        $data['user_id']  = $user->id;

        // Operator hanya bisa input untuk bidangnya sendiri
        if (!$user->isAdmin()) {
            $data['bidang_id'] = $user->bidang_id;
        }

        // Handle checkbox klasifikasi keamanan
        $data['is_biasa']          = $request->boolean('is_biasa');
        $data['is_terbatas']       = $request->boolean('is_terbatas');
        $data['is_rahasia']        = $request->boolean('is_rahasia');
        $data['is_sangat_rahasia'] = $request->boolean('is_sangat_rahasia');
        $data['is_aktif']          = $request->boolean('is_aktif');
        $data['is_inaktif']        = $request->boolean('is_inaktif');

        // Upload scan file
        if ($request->hasFile('scan_file')) {
            $data['scan_file'] = $this->uploadFile($request->file('scan_file'), 'arsip');
        }

        Arsip::create($data);

        return redirect()->route('arsip.index')->with('success', 'Arsip berhasil ditambahkan.');
    }

    /**
     * Detail arsip.
     */
    public function show(Arsip $arsip)
    {
        $this->authorizeArsip($arsip);
        return view('arsip.show', compact('arsip'));
    }

    /**
     * Form edit arsip.
     */
    public function edit(Arsip $arsip)
    {
        $this->authorizeArsip($arsip);
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        return view('arsip.edit', compact('arsip', 'bidangs'));
    }

    /**
     * Update arsip.
     */
    public function update(Request $request, Arsip $arsip)
    {
        $user = auth()->user();
        $this->authorizeArsip($arsip);

        $rules = $this->validationRules($user->isAdmin());
        $request->validate($rules);

        $data = $request->except('scan_file');

        // Handle checkbox
        $data['is_biasa']          = $request->boolean('is_biasa');
        $data['is_terbatas']       = $request->boolean('is_terbatas');
        $data['is_rahasia']        = $request->boolean('is_rahasia');
        $data['is_sangat_rahasia'] = $request->boolean('is_sangat_rahasia');
        $data['is_aktif']          = $request->boolean('is_aktif');
        $data['is_inaktif']        = $request->boolean('is_inaktif');

        if (!$user->isAdmin()) {
            unset($data['bidang_id']);
        }

        // Ganti scan file jika ada upload baru
        if ($request->hasFile('scan_file')) {
            if ($arsip->scan_file && File::exists(public_path($arsip->scan_file))) {
                File::delete(public_path($arsip->scan_file));
            }
            $data['scan_file'] = $this->uploadFile($request->file('scan_file'), 'arsip');
        }

        $arsip->update($data);

        return redirect()->route('arsip.index')->with('success', 'Arsip berhasil diperbarui.');
    }

    /**
     * Hapus arsip.
     */
    public function destroy(Arsip $arsip)
    {
        $this->authorizeArsip($arsip);

        if ($arsip->scan_file && File::exists(public_path($arsip->scan_file))) {
            File::delete(public_path($arsip->scan_file));
        }

        $arsip->delete();

        return redirect()->route('arsip.index')->with('success', 'Arsip berhasil dihapus.');
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    /**
     * Cek akses arsip berdasarkan bidang.
     */
    private function authorizeArsip(Arsip $arsip): void
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $arsip->bidang_id !== $user->bidang_id) {
            abort(403, 'Anda tidak memiliki akses ke arsip ini.');
        }
    }

    /**
     * Aturan validasi arsip.
     */
    private function validationRules(bool $isAdmin): array
    {
        $rules = [
            'kode_klasifikasi'    => ['required', 'string', 'max:50'],
            'no_berkas'           => ['nullable', 'string', 'max:255'],
            'uraian_berkas'       => ['required', 'string'],
            'kurun_waktu'         => ['nullable', 'string', 'max:100'],
            'jumlah_berkas'       => ['nullable', 'string', 'max:100'],
            'no_item_arsip'       => ['nullable', 'string', 'max:50'],
            'uraian_arsip'        => ['nullable', 'string'],
            'tanggal_diarsipkan'  => ['nullable', 'date'],
            'jumlah_halaman_bundle' => ['nullable', 'string', 'max:100'],
            'tingkat_perkembangan'  => ['nullable', 'string', 'max:100'],
            'lokasi_simpan'       => ['nullable', 'string'],
            'no_rak'              => ['nullable', 'string', 'max:50'],
            'no_boks'             => ['nullable', 'string', 'max:50'],
            'no_folder'           => ['nullable', 'string', 'max:50'],
            'is_biasa'            => ['boolean'],
            'is_terbatas'         => ['boolean'],
            'is_rahasia'          => ['boolean'],
            'is_sangat_rahasia'   => ['boolean'],
            'is_aktif'            => ['boolean'],
            'is_inaktif'          => ['boolean'],
            'nasib_akhir'         => ['nullable', 'string', 'max:255'],
            'scan_file'           => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];

        if ($isAdmin) {
            $rules['bidang_id'] = ['required', 'exists:bidang,id'];
        }

        return $rules;
    }

    /**
     * Upload file scan arsip.
     */
    private function uploadFile($file, string $folder): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path("uploads/{$folder}"), $filename);
        return "uploads/{$folder}/{$filename}";
    }
}