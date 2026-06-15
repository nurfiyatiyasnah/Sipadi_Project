<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TanggapanAduan extends Model
{
    use HasFactory;

    protected $table = 'tanggapan_aduan';

    protected $primaryKey = 'id_tanggapan';

    protected $fillable = [
        'id_aduan',
        'id_petugas',
        'isi_tanggapan',
        'status_setelah_respon',
        'ditanggapi_pada',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ditanggapi_pada' => 'datetime',
        ];
    }

    public function aduan(): BelongsTo
    {
        return $this->belongsTo(Aduan::class, 'id_aduan', 'id_aduan');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }
}
