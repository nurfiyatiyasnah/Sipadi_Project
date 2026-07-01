<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaEvent extends Model
{
    use HasFactory;

    protected $table = 'agenda_event';

    protected $primaryKey = 'id_event';

    protected $fillable = [
        'judul_event',
        'slug',
        'deskripsi',
        'lokasi',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'gambar',
        'status_event',
        'kategori',
        'tampilkan_beranda',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'tampilkan_beranda' => 'boolean',
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'created_by', 'id_petugas');
    }
}
