<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AturanPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'aturan_peminjaman';

    protected $primaryKey = 'id_aturan_peminjaman';

    protected $fillable = [
        'nama_aturan',
        'lama_pinjam_hari',
        'maksimal_buku_per_peminjaman',
        'maksimal_peminjam_aktif',
        'masa_suspend_per_hari_terlambat',
        'status_aktif',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
        ];
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'id_aturan', 'id_aturan_peminjaman');
    }
}
