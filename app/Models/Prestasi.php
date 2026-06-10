<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasi';

    protected $primaryKey = 'id_prestasi';

    protected $fillable = [
        'judul_prestasi',
        'slug',
        'deskripsi',
        'tingkat_prestasi',
        'penyelenggra',
        'tanggal_prestasi',
        'gambar',
        'file_lampiran',
        'status_prestasi',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_prestasi' => 'date',
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'created_by', 'id_petugas');
    }
}
