<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $primaryKey = 'id_berita';

    protected $fillable = [
        'id_kategori_berita',
        'id_petugas',
        'judul',
        'slug',
        'isi',
        'gambar',
        'tanggal_terbit',
        'status_berita',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_terbit' => 'date',
        ];
    }

    public function kategoriBerita(): BelongsTo
    {
        return $this->belongsTo(KategoriBerita::class, 'id_kategori_berita', 'id_kategori_berita');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }
}
