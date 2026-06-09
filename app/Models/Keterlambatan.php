<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keterlambatan extends Model
{
    use HasFactory;

    protected $table = 'keterlambatan';

    protected $fillable = [
        'peminjaman_id',
        'jumlah_hari',
        'denda_per_hari',
        'total_denda',
        'status_denda',
    ];

    protected $attributes = [
        'denda_per_hari' => 0,
        'total_denda' => 0,
        'status_denda' => 'Belum Bayar',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'denda_per_hari' => 'decimal:2',
            'total_denda' => 'decimal:2',
        ];
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }
}
