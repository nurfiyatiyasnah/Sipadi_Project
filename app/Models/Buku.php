<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

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

    public function scopeAktif(Builder $query): Builder
    {
        return $query->whereIn('status_katalog', ['aktif', 'Aktif']);
    }

    public function scopeWithKetersediaanCounts(Builder $query): Builder
    {
        return $query
            ->withCount('eksemplar')
            ->withCount([
                'eksemplar as eksemplar_tersedia_count' => fn (Builder $query) => $query
                    ->whereIn('status_eksemplar', EksemplarBuku::AVAILABLE_COPY_STATUSES),
                'eksemplar as eksemplar_dipinjam_count' => fn (Builder $query) => $query
                    ->whereIn('status_eksemplar', EksemplarBuku::BORROWED_COPY_STATUSES),
            ]);
    }

    public function isKatalogAktif(): bool
    {
        return strtolower((string) $this->status_katalog) === 'aktif';
    }

    public function totalEksemplarCount(): int
    {
        if ($this->relationLoaded('eksemplar')) {
            return $this->eksemplar->count();
        }

        if (array_key_exists('eksemplar_count', $this->getAttributes())) {
            return (int) $this->eksemplar_count;
        }

        return $this->eksemplar()->count();
    }

    public function availableEksemplarCount(): int
    {
        return $this->countEksemplarByStatuses(
            EksemplarBuku::AVAILABLE_COPY_STATUSES,
            'eksemplar_tersedia_count'
        );
    }

    public function borrowedEksemplarCount(): int
    {
        return $this->countEksemplarByStatuses(
            EksemplarBuku::BORROWED_COPY_STATUSES,
            'eksemplar_dipinjam_count'
        );
    }

    public function statusKetersediaan(bool $includeLowStock = true): string
    {
        if (! $this->isKatalogAktif()) {
            return 'nonaktif';
        }

        $totalEksemplar = $this->totalEksemplarCount();
        $tersediaCount = $this->availableEksemplarCount();

        if ($totalEksemplar === 0) {
            return 'stok_kosong';
        }

        if ($tersediaCount > 0) {
            return $includeLowStock && $tersediaCount < 3 ? 'stok_menipis' : 'tersedia';
        }

        if ($this->borrowedEksemplarCount() === $totalEksemplar) {
            return 'dipinjam_semua';
        }

        return 'tidak_tersedia';
    }

    public function statusKetersediaanLabel(bool $includeLowStock = true): string
    {
        return match ($this->statusKetersediaan($includeLowStock)) {
            'nonaktif' => 'Nonaktif',
            'stok_kosong' => 'Stok Kosong',
            'stok_menipis' => 'Stok Menipis',
            'dipinjam_semua' => 'Dipinjam Semua',
            'tidak_tersedia' => 'Tidak Tersedia',
            default => 'Tersedia',
        };
    }

    /**
     * @return Collection<int, string>
     */
    public function lokasiRakEksemplar(): Collection
    {
        $locations = $this->relationLoaded('eksemplar')
            ? $this->eksemplar->pluck('lokasi_rak')
            : $this->eksemplar()
                ->whereNotNull('lokasi_rak')
                ->orderBy('id_eksemplar_buku')
                ->pluck('lokasi_rak');

        return $locations
            ->map(fn (mixed $location): string => trim((string) $location))
            ->filter(fn (string $location): bool => $location !== '')
            ->unique()
            ->values();
    }

    public function lokasiRakEksemplarLabel(): string
    {
        $locations = $this->lokasiRakEksemplar();

        return $locations->isEmpty() ? 'Belum diatur' : $locations->implode(', ');
    }

    private function countEksemplarByStatuses(array $statuses, string $countAttribute): int
    {
        if ($this->relationLoaded('eksemplar')) {
            $normalizedStatuses = array_map('strtolower', $statuses);

            return $this->eksemplar
                ->filter(fn (EksemplarBuku $copy): bool => in_array(strtolower((string) $copy->status_eksemplar), $normalizedStatuses, true))
                ->count();
        }

        if (array_key_exists($countAttribute, $this->getAttributes())) {
            return (int) $this->{$countAttribute};
        }

        return $this->eksemplar()
            ->whereIn('status_eksemplar', $statuses)
            ->count();
    }
}
