<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPengembalian extends Model
{
    use HasFactory;

    protected $table = 'detail_pengembalian';

    protected $fillable = [
        'pengembalian_id',
        'detail_peminjaman_id',
        'kondisi_buku',
        'catatan',
    ];

    public function pengembalian(): BelongsTo
    {
        return $this->belongsTo(Pengembalian::class);
    }

    public function detailPeminjaman(): BelongsTo
    {
        return $this->belongsTo(DetailPeminjaman::class);
    }
}
