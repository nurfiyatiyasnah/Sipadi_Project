<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalPengambilan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pengambilan';

    protected $primaryKey = 'id_jadwal_pengambilan';

    protected $fillable = [
        'id_peminjaman',
        'id_petugas',
        'tanggal_pengambilan',
        'jam_mulai',
        'jam_selesai',
        'lokasi_pengambilan',
        'pesan',
        'status_jadwal',
        'dikirim_pada',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_pengambilan' => 'date',
            'dikirim_pada' => 'datetime',
        ];
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_jadwal_pengambilan', 'id_jadwal_pengambilan');
    }
}
