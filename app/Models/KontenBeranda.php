<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontenBeranda extends Model
{
    use HasFactory;

    protected $table = 'konten_beranda';

    protected $primaryKey = 'id_konten_beranda';

    protected $fillable = [
        'judul',
        'isi',
        'gambar',
        'urutan',
        'status_konten',
    ];

    protected $attributes = [
        'urutan' => 0,
    ];
}
