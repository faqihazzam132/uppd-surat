<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nik',
        'no_hp',
        'role',
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
        ];
    }

    // Relasi: User (Pemohon) punya banyak Pengajuan
    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class);
    }

    /**
     * Riwayat log pengajuan yang dibuat / diubah oleh user ini.
     */
    public function pengajuanLogs(): HasMany
    {
        return $this->hasMany(PengajuanLog::class);
    }
}