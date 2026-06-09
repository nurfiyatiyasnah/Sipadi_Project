<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatAnggota extends Model
{
    public $timestamps = false;

    protected $table = 'riwayat_anggota';

    protected $fillable = [
        'anggota_id',
        'peminjaman_id',
        'pengembalian_id',
        'jenis_aktivitas',
        'deskripsi',
        'created_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function pengembalian(): BelongsTo
    {
        return $this->belongsTo(Pengembalian::class);
    }
}
