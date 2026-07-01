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

    protected $primaryKey = 'id_buku';

    protected $fillable = [
        'id_kategori',
        'kode_buku',
        'isbn',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'deskripsi',
        'gambar_cover',
        'status_katalog',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBuku::class, 'id_kategori', 'id_kategori');
    }

    public function eksemplar(): HasMany
    {
        return $this->hasMany(EksemplarBuku::class, 'id_buku', 'id_buku');
    }

    public function mutasiStok(): HasMany
    {
        return $this->hasMany(MutasiStokBuku::class, 'id_buku', 'id_buku');
    }

    public function detailPeminjaman(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_buku', 'id_buku');
    }

    public function getJumlahHalamanAttribute(): int
    {
        if (str_contains(strtolower($this->judul), 'laskar pelangi')) {
            return 529;
        }
        if (str_contains(strtolower($this->judul), 'tenggelamnya kapal')) {
            return 224;
        }
        if (str_contains(strtolower($this->judul), 'sang pemimpi')) {
            return 292;
        }
        if (str_contains(strtolower($this->judul), 'bumi manusia')) {
            return 535;
        }
        if (str_contains(strtolower($this->judul), 'negeri 5 menara')) {
            return 423;
        }
        if (str_contains(strtolower($this->judul), 'hujan bulan juni')) {
            return 144;
        }
        return 320;
    }

    public function getRatingAttribute(): float
    {
        if (str_contains(strtolower($this->judul), 'laskar pelangi')) {
            return 4.8;
        }
        if (str_contains(strtolower($this->judul), 'tenggelamnya kapal')) {
            return 4.7;
        }
        if (str_contains(strtolower($this->judul), 'sang pemimpi')) {
            return 4.6;
        }
        if (str_contains(strtolower($this->judul), 'bumi manusia')) {
            return 4.9;
        }
        if (str_contains(strtolower($this->judul), 'negeri 5 menara')) {
            return 4.5;
        }
        return 4.5;
    }
}
