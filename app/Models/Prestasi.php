<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestasi extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'terbit';

    public const STATUS_INACTIVE = 'nonaktif';

    protected $table = 'prestasi';

    protected $primaryKey = 'id_prestasi';

    protected $fillable = [
        'judul_prestasi',
        'slug',
        'deskripsi',
        'tingkat_prestasi',
        'penyelenggara',
        'nomor_sertifikat',
        'tanggal_prestasi',
        'gambar',
        'file_lampiran',
        'status_prestasi',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_prestasi' => 'date',
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'created_by', 'id_petugas');
    }

    /**
     * @param  Builder<Prestasi>  $query
     * @return Builder<Prestasi>
     */
    public function scopeFilter(Builder $query, ?string $search = null, ?string $tingkat = null, ?string $status = null, ?string $tahun = null): Builder
    {
        return $query
            ->when($search, fn (Builder $q, string $search) => $q->where(function (Builder $query) use ($search): void {
                $query
                    ->where('judul_prestasi', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('penyelenggara', 'like', "%{$search}%")
                    ->orWhere('nomor_sertifikat', 'like', "%{$search}%");
            }))
            ->when($tingkat, fn (Builder $q, string $tingkat) => $q->where('tingkat_prestasi', $tingkat))
            ->when($status, fn (Builder $q, string $status) => $q->where('status_prestasi', $status))
            ->when($tahun, fn (Builder $q, string $tahun) => $q->whereYear('tanggal_prestasi', $tahun));
    }
}
