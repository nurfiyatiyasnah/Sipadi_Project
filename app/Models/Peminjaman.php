<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'ajukan_peminjaman_id',
        'anggota_id',
        'tanggal_pinjam',
        'tanggal_harus_kembali',
        'status_peminjaman',
        'petugas_id',
    ];

    protected $attributes = [
        'status_peminjaman' => 'Aktif',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_harus_kembali' => 'date',
        ];
    }

    public function ajukanPeminjaman(): BelongsTo
    {
        return $this->belongsTo(AjukanPeminjaman::class);
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class);
    }

    public function detailPeminjaman(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function jadwalPengambilan(): HasOne
    {
        return $this->hasOne(JadwalPengambilan::class);
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class);
    }

    public function keterlambatan(): HasOne
    {
        return $this->hasOne(Keterlambatan::class);
    }
}
