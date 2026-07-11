<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Keterlambatan extends Model
{
    use HasFactory;

    protected $table = 'keterlambatan';

    protected $primaryKey = 'id_keterlambatan';

    protected $fillable = [
        'id_peminjaman',
        'id_anggota',
        'tanggal_jatuh_tempo',
        'tanggal_dihitung',
        'hari_terlambat',
        'status_perhitungan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_jatuh_tempo' => 'date',
            'tanggal_dihitung' => 'date',
        ];
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function sanksiAnggota(): HasOne
    {
        return $this->hasOne(SanksiAnggota::class, 'id_keterlambatan', 'id_keterlambatan');
    }
}
