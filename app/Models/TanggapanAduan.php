<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TanggapanAduan extends Model
{
    use HasFactory;

    protected $table = 'tanggapan_aduan';

    protected $fillable = [
        'aduan_id',
        'user_id',
        'isi_tanggapan',
    ];

    public function aduan(): BelongsTo
    {
        return $this->belongsTo(Aduan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
