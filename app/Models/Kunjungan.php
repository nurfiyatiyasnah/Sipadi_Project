<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungan';

    protected $primaryKey = 'id_kunjugan';

    protected $fillable = [
        'kode_kunjungan',
        'id_anggota',
        'nama_pengunjung',
        'jenis_pengunjung',
        'no_identitas',
        'no_hp',
        'instansi',
        'jumlah_kunjungan',
        'tujuan_kunjungan',
        'tanggal_kunjungan',
        'jam_masuk',
        'jam_keluar',
        'status_kunjungan',
        'dicatat_oleh',
    ];

    protected $attributes = [
        'jumlah_kunjungan' => 1,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_kunjungan' => 'date',
        ];
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function dicatatOleh(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'dicatat_oleh', 'id_petugas');
    }
}
