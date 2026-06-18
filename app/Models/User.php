<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, MustVerifyEmailTrait, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'id_role',
        'email',
        'password',
        'status_akun',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    public function anggota(): HasOne
    {
        return $this->hasOne(Anggota::class, 'id_user', 'id_user');
    }

    public function petugas(): HasOne
    {
        return $this->hasOne(Petugas::class, 'id_user', 'id_user');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_user', 'id_user');
    }

    public function logPencarianBuku(): HasMany
    {
        return $this->hasMany(LogPencarianBuku::class, 'id_user', 'id_user');
    }

    public function isPetugas(): bool
    {
        return $this->role?->nama_role === 'Petugas';
    }

    public function isAnggota(): bool
    {
        return $this->role?->nama_role === 'Anggota';
    }

    public function getNamaAttribute(): string
    {
        return $this->anggota?->nama_lengkap
            ?? $this->petugas?->nama_petugas
            ?? $this->email;
    }

    public function getNameAttribute(): string
    {
        return $this->nama;
    }
}
