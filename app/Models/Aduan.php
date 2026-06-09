<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aduan extends Model
{
    use HasFactory;

    protected $table = 'aduan';

    protected $fillable = [
        'user_id',
        'nama_pelapor',
        'email_pelapor',
        'subjek',
        'isi_aduan',
        'status_aduan',
        'tanggapan',
        'ditanggapi_oleh',
        'ditanggapi_at',
    ];

    protected $attributes = [
        'status_aduan' => 'Baru',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ditanggapi_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ditanggapiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditanggapi_oleh');
    }

    public function tanggapanAduan(): HasMany
    {
        return $this->hasMany(TanggapanAduan::class);
    }
}
