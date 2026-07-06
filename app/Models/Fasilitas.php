<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';

    protected $primaryKey = 'id_fasilitas';

    protected $fillable = [
        'nama_fasilitas',
        'slug',
        'deskripsi',
        'lokasi',
        'jumlah_unit',
        'gambar',
        'status_fasilitas',
        'created_by',
        'kategori',
        'satuan_kapasitas',
        'kelengkapan',
        'tampilkan_publik',
        'aktifkan_reservasi',
        'metode_peminjaman',
        'durasi_maksimal',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kelengkapan' => 'array',
            'tampilkan_publik' => 'boolean',
            'aktifkan_reservasi' => 'boolean',
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'created_by', 'id_petugas');
    }
}
