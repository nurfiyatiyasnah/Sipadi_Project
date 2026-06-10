<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'kategori',
        'lokasi_rak',
        'stok',
        'status',
        'sinopsis',
        'cover'
    ];
}
