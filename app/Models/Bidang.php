<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'bidang';

    protected $fillable = [
        'nama_bidang',
        'kode_bidang',
    ];

    /**
     * Get the users associated with the bidang.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'bidang_id');
    }

    /**
     * Get the incoming mail associated with the bidang.
     */
    public function suratMasuks()
    {
        return $this->hasMany(SuratMasuk::class, 'bidang_id');
    }

    /**
     * Get the outgoing mail associated with the bidang.
     */
    public function suratKeluars()
    {
        return $this->hasMany(SuratKeluar::class, 'bidang_id');
    }
}
