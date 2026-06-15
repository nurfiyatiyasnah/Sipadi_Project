<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_user';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id_role',
        'name',
        'email',
        'password',
        'status_akun',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Keep Laravel auth scaffolding compatible with the custom id_user key.
     */
    protected function id(): Attribute
    {
        return Attribute::get(fn (): mixed => $this->getAttribute($this->getKeyName()));
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
}
