<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPengambilan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pengambilan';

    protected $fillable = [
        'peminjaman_id',
        'tanggal_pengambilan',
        'waktu_mulai',
        'waktu_selesai',
        'status_pengambilan',
        'catatan',
    ];

    protected $attributes = [
        'status_pengambilan' => 'Dijadwalkan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_pengambilan' => 'date',
        ];
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }
}
