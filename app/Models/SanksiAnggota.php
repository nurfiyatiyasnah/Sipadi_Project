<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SanksiAnggota extends Model
{
    use HasFactory;

    protected $table = 'sanksi_anggota';

    protected $primaryKey = 'id_sanksi_anggota';

    protected $fillable = [
        'id_anggota',
        'id_peminjaman',
        'id_keterlambatan',
        'jenis_sanksi',
        'alasan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_sanksi',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function keterlambatan(): BelongsTo
    {
        return $this->belongsTo(Keterlambatan::class, 'id_keterlambatan', 'id_keterlambatan');
    }
}
