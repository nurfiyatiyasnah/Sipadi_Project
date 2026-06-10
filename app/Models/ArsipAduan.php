<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipAduan extends Model
{
    use HasFactory;

    protected $table = 'arsip_aduan';

    protected $primaryKey = 'id_arsip_aduan';

    protected $fillable = [
        'id_aduan',
        'diarsipkan_oleh',
        'alasan_diarsipkan',
        'diarsipkan_pada',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'diarsipkan_pada' => 'datetime',
        ];
    }

    public function aduan(): BelongsTo
    {
        return $this->belongsTo(Aduan::class, 'id_aduan', 'id_aduan');
    }

    public function diarsipkanOleh(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'diarsipkan_oleh', 'id_petugas');
    }
}
