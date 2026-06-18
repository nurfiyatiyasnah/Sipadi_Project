<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EksemplarBuku extends Model
{
    use HasFactory;

    protected $table = 'eksemplar_buku';

    protected $primaryKey = 'id_eksemplar_buku';

    protected $fillable = [
        'id_buku',
        'kode_eksemplar',
        'status_eksemplar',
        'kondisi_eksemplar',
        'lokasi_rak',
        'tanggal_masuk',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_masuk' => 'date',
        ];
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }
}
