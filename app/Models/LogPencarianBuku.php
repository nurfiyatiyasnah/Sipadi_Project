<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogPencarianBuku extends Model
{
    use HasFactory;

    protected $table = 'log_pencarian_buku';

    protected $primaryKey = 'id_log_pencarian';

    protected $fillable = [
        'id_user',
        'keyword',
        'jumlah_hasil',
        'ip_address',
        'user_agent',
    ];

    protected $attributes = [
        'jumlah_hasil' => 0,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
