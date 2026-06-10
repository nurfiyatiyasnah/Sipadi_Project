<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EksemplarBuku extends Model
{
    use HasFactory;

    protected $table = 'eksemplar_buku';

    protected $fillable = [
        'buku_id',
        'kode_eksemplar',
        'kondisi',
        'status',
        'catatan',
    ];

    protected $attributes = [
        'kondisi' => 'Baik',
        'status' => 'Tersedia',
    ];

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }
}
