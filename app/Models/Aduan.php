<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Aduan extends Model
{
    use HasFactory;

    protected $table = 'aduan';

    protected $primaryKey = 'id_aduan';

    protected $fillable = [
        'kode_aduan',
        'id_anggota',
        'subjek',
        'isi_aduan',
        'kategori_aduan',
        'lampiran',
        'status_aduan',
        'prioritas',
    ];

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function tanggapan(): HasMany
    {
        return $this->hasMany(TanggapanAduan::class, 'id_aduan', 'id_aduan');
    }

    public function arsip(): HasOne
    {
        return $this->hasOne(ArsipAduan::class, 'id_aduan', 'id_aduan');
    }
}
