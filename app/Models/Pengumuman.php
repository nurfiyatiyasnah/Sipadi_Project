<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $primaryKey = 'id_pengumuman';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'id_petugas',
        'judul',
        'slug',
        'isi',
        'gambar',
        'file_lampiran',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_pengumuman',
        'prioritas',
        'target_pengguna',
        'total_views',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'file_lampiran' => 'array',
        ];
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->status_pengumuman === 'draf') {
            return 'Draf';
        }

        $today = now()->startOfDay();
        $start = $this->tanggal_mulai ? $this->tanggal_mulai->startOfDay() : null;
        $end = $this->tanggal_selesai ? $this->tanggal_selesai->endOfDay() : null;

        if ($start && $today->lt($start)) {
            return 'Mendatang';
        }

        if ($end && $today->gt($end)) {
            return 'Selesai';
        }

        return 'Aktif';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        $status = $this->status_label;

        return match ($status) {
            'Aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'Mendatang' => 'bg-amber-50 text-amber-700 border-amber-200',
            'Selesai' => 'bg-slate-100 text-slate-700 border-slate-200',
            default => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    }
}
