<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $table = 'arsip';

    protected $fillable = [
        'kode_klasifikasi',
        'no_berkas',
        'uraian_berkas',
        'kurun_waktu',
        'jumlah_berkas',
        'no_item_arsip',
        'uraian_arsip',
        'tanggal_diarsipkan',
        'jumlah_halaman_bundle',
        'tingkat_perkembangan',
        'lokasi_simpan',
        'no_rak',
        'no_boks',
        'no_folder',
        'is_biasa',
        'is_terbatas',
        'is_rahasia',
        'is_sangat_rahasia',
        'is_aktif',
        'is_inaktif',
        'nasib_akhir',
        'scan_file',
        'bidang_id',
        'user_id',
    ];

    protected $casts = [
        'tanggal_diarsipkan' => 'date',
        'is_biasa'           => 'boolean',
        'is_terbatas'        => 'boolean',
        'is_rahasia'         => 'boolean',
        'is_sangat_rahasia'  => 'boolean',
        'is_aktif'           => 'boolean',
        'is_inaktif'         => 'boolean',
    ];

    /**
     * Nilai default untuk klasifikasi keamanan.
     */
    protected $attributes = [
        'is_biasa'          => true,
        'is_terbatas'       => false,
        'is_rahasia'        => false,
        'is_sangat_rahasia' => false,
        'is_aktif'          => true,
        'is_inaktif'        => false,
        'lokasi_simpan'     => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
    ];

    /**
     * Relasi ke bidang yang memiliki arsip ini.
     */
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    /**
     * Relasi ke user (operator) yang menginput arsip ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mendapatkan label klasifikasi keamanan arsip.
     */
    public function getKlasifikasiKeamananAttribute(): string
    {
        if ($this->is_sangat_rahasia) return 'Sangat Rahasia';
        if ($this->is_rahasia) return 'Rahasia';
        if ($this->is_terbatas) return 'Terbatas';
        return 'Biasa';
    }

    /**
     * Mendapatkan label status retensi arsip.
     */
    public function getStatusRetensiAttribute(): string
    {
        if ($this->is_aktif && $this->is_inaktif) return 'Aktif & Inaktif';
        if ($this->is_inaktif) return 'Inaktif';
        if ($this->is_aktif) return 'Aktif';
        return '-';
    }

    /**
     * Mendapatkan lokasi fisik arsip (rak/boks/folder).
     */
    public function getLokasiLengkapAttribute(): string
    {
        $parts = [];
        if ($this->no_rak) $parts[] = 'Rak ' . $this->no_rak;
        if ($this->no_boks) $parts[] = 'Boks ' . $this->no_boks;
        if ($this->no_folder) $parts[] = 'Folder ' . $this->no_folder;
        return implode(' / ', $parts) ?: '-';
    }

    /**
     * Scope untuk arsip aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true)->where('is_inaktif', false);
    }

    /**
     * Scope untuk arsip inaktif.
     */
    public function scopeInaktif($query)
    {
        return $query->where('is_inaktif', true);
    }

    /**
     * Scope untuk filter berdasarkan klasifikasi keamanan.
     */
    public function scopeKeamanan($query, string $level)
    {
        return match ($level) {
            'biasa'          => $query->where('is_biasa', true),
            'terbatas'       => $query->where('is_terbatas', true),
            'rahasia'        => $query->where('is_rahasia', true),
            'sangat_rahasia' => $query->where('is_sangat_rahasia', true),
            default          => $query,
        };
    }

    /**
     * Scope pencarian teks.
     */
    public function scopeCari($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('kode_klasifikasi', 'like', "%{$keyword}%")
              ->orWhere('no_berkas', 'like', "%{$keyword}%")
              ->orWhere('uraian_berkas', 'like', "%{$keyword}%")
              ->orWhere('uraian_arsip', 'like', "%{$keyword}%")
              ->orWhere('no_rak', 'like', "%{$keyword}%")
              ->orWhere('no_boks', 'like', "%{$keyword}%");
        });
    }
}