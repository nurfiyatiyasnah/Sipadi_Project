<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AjukanPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'ajukan_peminjaman';

    protected $fillable = [
        'anggota_id',
        'tanggal_pengajuan',
        'status_pengajuan',
        'catatan_anggota',
        'catatan_admin',
        'diproses_oleh',
    ];

    protected $attributes = [
        'status_pengajuan' => 'Menunggu',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'date',
        ];
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function diprosesOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function peminjaman(): HasOne
    {
        return $this->hasOne(Peminjaman::class);
    }
}
