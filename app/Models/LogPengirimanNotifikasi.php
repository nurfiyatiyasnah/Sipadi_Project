<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogPengirimanNotifikasi extends Model
{
    use HasFactory;

    protected $table = 'log_pengiriman_notifikasi';

    protected $primaryKey = 'id_pengiriman_notifikasi';

    protected $fillable = [
        'id_notifikasi',
        'dikirim_oleh',
        'via',
        'status_pengiriman',
        'pesan_error',
        'dikirim_pada',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'dikirim_pada' => 'datetime',
        ];
    }

    public function notifikasi(): BelongsTo
    {
        return $this->belongsTo(Notifikasi::class, 'id_notifikasi', 'id_notifikasi');
    }

    public function dikirimOleh(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'dikirim_oleh', 'id_petugas');
    }
}
