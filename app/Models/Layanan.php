<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';

    protected $primaryKey = 'id_layanan';

    protected $fillable = [
        'nama_layanan',
        'slug',
        'deskripsi',
        'persyaratan',
        'prosedur',
        'jam_layanan',
        'biaya',
        'kontak_layanan',
        'gambar',
        'status_layanan',
        'created_by',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'created_by', 'id_petugas');
    }
}
