<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'jabatan',
        'no_hp',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
