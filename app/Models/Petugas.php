<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';

    protected $primaryKey = 'id_petugas';

    protected $fillable = [
        'id_user',
        'nama_petugas',
        'jabatan',
        'no_hp',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'id_petugas', 'id_petugas');
    }

    public function jadwalPengambilan(): HasMany
    {
        return $this->hasMany(JadwalPengambilan::class, 'id_petugas', 'id_petugas');
    }

    public function pengembalian(): HasMany
    {
        return $this->hasMany(Pengembalian::class, 'id_petugas', 'id_petugas');
    }

    public function berita(): HasMany
    {
        return $this->hasMany(Berita::class, 'id_petugas', 'id_petugas');
    }

    public function pengumuman(): HasMany
    {
        return $this->hasMany(Pengumuman::class, 'id_petugas', 'id_petugas');
    }

    public function mutasiStokBuku(): HasMany
    {
        return $this->hasMany(MutasiStokBuku::class, 'id_petugas', 'id_petugas');
    }

    public function tanggapanAduan(): HasMany
    {
        return $this->hasMany(TanggapanAduan::class, 'id_petugas', 'id_petugas');
    }
}
