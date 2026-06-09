<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';

    protected $fillable = [
        'peminjaman_id',
        'eksemplar_id',
        'buku_id',
        'status_item',
    ];

    protected $attributes = [
        'status_item' => 'Dipinjam',
    ];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function eksemplar(): BelongsTo
    {
        return $this->belongsTo(EksemplarBuku::class, 'eksemplar_id');
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }

    public function detailPengembalian(): HasOne
    {
        return $this->hasOne(DetailPengembalian::class);
    }
}
