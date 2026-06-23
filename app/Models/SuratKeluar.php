<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluar';

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tujuan',
        'perihal',
        'boks_id',
        'user_id',
        'bidang_id',
        'scan_file',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    /**
     * Get the box where this letter is physically stored.
     */
    public function boks()
    {
        return $this->belongsTo(Boks::class, 'boks_id');
    }

    /**
     * Get the user (operator) who recorded this letter.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the bidang (department/division) that owns this letter.
     */
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }
}
