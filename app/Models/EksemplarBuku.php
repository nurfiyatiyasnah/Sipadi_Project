<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EksemplarBuku extends Model
{
    use HasFactory;

    public const ACTIVE_BORROWING_STATUSES = ['aktif', 'terlambat', 'siap_diambil'];

    public const ACTIVE_DETAIL_STATUSES = ['dipinjam', 'dipesan'];

    protected $table = 'eksemplar_buku';

    protected $primaryKey = 'id_eksemplar_buku';

    protected $fillable = [
        'id_buku',
        'kode_eksemplar',
        'status_eksemplar',
        'kondisi_eksemplar',
        'lokasi_rak',
        'tanggal_masuk',
        'sumber_perolehan',
        'catatan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_masuk' => 'date',
        ];
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    public function detailPeminjaman(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_eksemplar_buku', 'id_eksemplar_buku');
    }

    public function hasActiveBorrowing(): bool
    {
        return $this->detailPeminjaman()
            ->whereIn('status_detail', self::ACTIVE_DETAIL_STATUSES)
            ->whereHas('peminjaman', function (Builder $query): void {
                $query->whereIn('status_peminjaman', self::ACTIVE_BORROWING_STATUSES);
            })
            ->exists();
    }
}
