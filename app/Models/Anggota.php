<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $primaryKey = 'id_anggota';

    protected $fillable = [
        'id_user',
        'no_anggota',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'foto',
        'tanggal_daftar',
        'status_anggota',
        'alasan_nonaktif',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_daftar' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'id_anggota', 'id_anggota');
    }

    public function keterlambatan(): HasMany
    {
        return $this->hasMany(Keterlambatan::class, 'id_anggota', 'id_anggota');
    }

    public function sanksi(): HasMany
    {
        return $this->hasMany(SanksiAnggota::class, 'id_anggota', 'id_anggota');
    }

    public function aduan(): HasMany
    {
        return $this->hasMany(Aduan::class, 'id_anggota', 'id_anggota');
    }

    public function kunjungan(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'id_anggota', 'id_anggota');
    }

    public function eKartuAnggota(): HasOne
    {
        return $this->hasOne(EKartuAnggota::class, 'id_anggota', 'id_anggota');
    }
}
