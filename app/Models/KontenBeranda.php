<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontenBeranda extends Model
{
    use HasFactory;

    protected $table = 'konten_beranda';

    protected $fillable = [
        'section',
        'judul',
        'konten',
        'gambar',
        'urutan',
        'is_active',
    ];

    protected $attributes = [
        'urutan' => 0,
        'is_active' => true,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
