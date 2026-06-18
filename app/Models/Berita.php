<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Berita extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'terbit';

    protected $table = 'berita';

    protected $primaryKey = 'id_berita';

    protected $fillable = [
        'id_kategori_berita',
        'id_petugas',
        'judul',
        'slug',
        'isi',
        'gambar',
        'tanggal_terbit',
        'status_berita',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_terbit' => 'date',
        ];
    }

    public function kategoriBerita(): BelongsTo
    {
        return $this->belongsTo(KategoriBerita::class, 'id_kategori_berita', 'id_kategori_berita');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    /**
     * @param  Builder<Berita>  $query
     * @return Builder<Berita>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status_berita', self::STATUS_PUBLISHED);
    }

    /**
     * @param  Builder<Berita>  $query
     * @return Builder<Berita>
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status_berita', self::STATUS_DRAFT);
    }

    /**
     * @param  Builder<Berita>  $query
     * @return Builder<Berita>
     */
    public function scopeFilter(Builder $query, ?string $search = null, ?string $kategori = null, ?string $status = null): Builder
    {
        return $query
            ->when($search, fn (Builder $q, string $search) => $q->where(function (Builder $query) use ($search): void {
                $query
                    ->where('judul', 'like', "%{$search}%")
                    ->orWhere('isi', 'like', "%{$search}%");
            }))
            ->when($kategori, fn (Builder $q, string $kategori) => $q->where('id_kategori_berita', $kategori))
            ->when($status, fn (Builder $q, string $status) => $q->where('status_berita', $status));
    }
}
