<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogPencarianBuku extends Model
{
    public $timestamps = false;

    protected $table = 'log_pencarian_buku';

    protected $fillable = [
        'user_id',
        'kata_kunci',
        'jumlah_hasil',
        'created_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
