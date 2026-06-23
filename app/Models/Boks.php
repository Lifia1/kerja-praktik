<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boks extends Model
{
    use HasFactory;

    protected $table = 'boks';

    protected $fillable = [
        'nomor_boks',
        'lokasi_rak',
        'keterangan',
    ];

    /**
     * Get the incoming mail associated with the box.
     */
    public function suratMasuks()
    {
        return $this->hasMany(SuratMasuk::class, 'boks_id');
    }

    /**
     * Get the outgoing mail associated with the box.
     */
    public function suratKeluars()
    {
        return $this->hasMany(SuratKeluar::class, 'boks_id');
    }
}
