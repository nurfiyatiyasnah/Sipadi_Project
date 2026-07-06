<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiStokBuku extends Model
{
    use HasFactory;

    protected $table = 'mutasi_stok_buku';

    protected $primaryKey = 'id_mutasi_stok_buku';

    public $timestamps = false;

    protected $fillable = [
        'id_buku',
        'id_petugas',
        'jenis_mutasi',
        'jumlah',
        'stok_total_sebelum',
        'stok_total_sesudah',
        'stok_tersedia_sebelum',
        'stok_tersedia_sesudah',
    ];

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }
}
