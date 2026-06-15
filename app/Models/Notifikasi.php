<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $primaryKey = 'id_notifikasi';

    protected $fillable = [
        'id_user',
        'id_peminjaman',
        'id_jadwal_pengambalian',
        'judul',
        'isi',
        'jenis_notifikasi',
        'status_notifikasi',
        'status_baca',
        'dikirim_pada',
        'dibaca_pada',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'dikirim_pada' => 'datetime',
            'dibaca_pada' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function jadwalPengambilan(): BelongsTo
    {
        return $this->belongsTo(JadwalPengambilan::class, 'id_jadwal_pengambalian', 'id_jadwal_pengambilan');
    }

    public function logPengiriman(): HasMany
    {
        return $this->hasMany(LogPengirimanNotifikasi::class, 'id_notifikasi', 'id_notifikasi');
    }
}
