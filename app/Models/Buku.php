<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'kategori_id',
        'judul',
        'isbn',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'edisi',
        'bahasa',
        'jumlah_halaman',
        'deskripsi',
        'cover',
        'lokasi_rak',
        'stok_total',
        'stok_tersedia',
    ];

    protected $attributes = [
        'stok_total' => 0,
        'stok_tersedia' => 0,
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBuku::class, 'kategori_id');
    }

    public function eksemplar(): HasMany
    {
        return $this->hasMany(EksemplarBuku::class);
    }

    public function mutasiStok(): HasMany
    {
        return $this->hasMany(MutasiStokBuku::class);
    }
}
