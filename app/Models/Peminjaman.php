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

    protected $primaryKey = 'id_peminjaman';

    protected $fillable = [
        'kode_peminjaman',
        'id_anggota',
        'id_aturan',
        'id_petugas',
        'tanggal_pengajuan',
        'deskripsi_pengajuan',
        'tanggal_diambil',
        'tanggal_jatuh_tempo',
        'status_peminjaman',
        'catatan_admin',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'datetime',
            'tanggal_diambil' => 'datetime',
            'tanggal_jatuh_tempo' => 'date',
        ];
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function aturanPeminjaman(): BelongsTo
    {
        return $this->belongsTo(AturanPeminjaman::class, 'id_aturan', 'id_aturan_peminjaman');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    public function detailPeminjaman(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function jadwalPengambilan(): HasOne
    {
        return $this->hasOne(JadwalPengambilan::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function keterlambatan(): HasOne
    {
        return $this->hasOne(Keterlambatan::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function sanksiAnggota(): HasOne
    {
        return $this->hasOne(SanksiAnggota::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_peminjaman', 'id_peminjaman');
    }
}
