<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogPengirimanNotifikasi extends Model
{
    public $timestamps = false;

    protected $table = 'log_pengiriman_notifikasi';

    protected $fillable = [
        'notifikasi_id',
        'metode',
        'status',
        'created_at',
    ];

    protected $attributes = [
        'status' => 'Pending',
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

    public function notifikasi(): BelongsTo
    {
        return $this->belongsTo(Notifikasi::class);
    }
}
