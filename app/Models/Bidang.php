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
     * Relasi ke users yang berasosiasi dengan bidang ini.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'bidang_id');
    }

    /**
     * Relasi ke arsip yang dimiliki bidang ini.
     */
    public function arsips()
    {
        return $this->hasMany(Arsip::class, 'bidang_id');
    }
}