<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrukturKepegawaian extends Model
{
    protected $table = 'struktur_kepegawaian';
    protected $primaryKey = 'id_struktur_kepegawaian';

    protected $fillable = [
        'nama',
        'jabatan',
        'foto',
        'urutan',
    ];
}
