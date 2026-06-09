<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiStokBuku extends Model
{
    public $timestamps = false;

    protected $table = 'mutasi_stok_buku';

    protected $fillable = [
        'buku_id',
        'jenis_mutasi',
        'jumlah',
        'keterangan',
        'petugas_id',
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

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class);
    }
}
