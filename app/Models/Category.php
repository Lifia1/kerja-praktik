<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama_kategori', 'deskripsi'])]
class Category extends Model
{
    use HasFactory;

    /**
     * Get the archives associated with the category.
     */
    public function archives()
    {
        return $this->hasMany(Archive::class);
    }
}
