<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';

    protected $fillable = [
        'peminjaman_id',
        'tanggal_pengembalian',
        'diterima_oleh',
        'denda_keterlambatan',
        'status_pengembalian',
        'catatan',
    ];

    protected $attributes = [
        'denda_keterlambatan' => 0,
        'status_pengembalian' => 'Selesai',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_pengembalian' => 'date',
            'denda_keterlambatan' => 'decimal:2',
        ];
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function diterimaOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diterima_oleh');
    }

    public function detailPengembalian(): HasMany
    {
        return $this->hasMany(DetailPengembalian::class);
    }
}
