<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama_bidang'])]
class Bidang extends Model
{
    use HasFactory;

    /**
     * Get the users associated with the bidang.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the archives associated with the bidang.
     */
    public function archives()
    {
        return $this->hasMany(Archive::class);
    }
}
