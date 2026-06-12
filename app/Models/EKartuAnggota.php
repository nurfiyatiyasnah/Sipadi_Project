<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EKartuAnggota extends Model
{
    use HasFactory;

    protected $table = 'e_kartu_anggota';

    protected $primaryKey = 'id_e_kartu_anggota';

    protected $fillable = [
        'id_anggota',
        'no_anggota',
        'kalangan',
        'barcode',
        'masa_berlaku',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'masa_berlaku' => 'date',
        ];
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }
}
