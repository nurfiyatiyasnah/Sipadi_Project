<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPengembalian extends Model
{
    use HasFactory;

    protected $table = 'detail_pengembalian';

    protected $primaryKey = 'id_detail_pengembalian';

    protected $fillable = [
        'id_pengembalian',
        'id_detail_peminjaman',
        'jumlah_dikembalikan',
        'kondisi_buku',
        'catatan',
    ];

    public function pengembalian(): BelongsTo
    {
        return $this->belongsTo(Pengembalian::class, 'id_pengembalian', 'id_pengembalian');
    }

    public function detailPeminjaman(): BelongsTo
    {
        return $this->belongsTo(DetailPeminjaman::class, 'id_detail_peminjaman', 'id_detail_peminjaman');
    }
}
