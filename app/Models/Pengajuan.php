<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_registrasi',
        'user_id',
        'jenis_surat',
        'keterangan',
        'file_syarat',
        'status',
        'catatan_petugas',
    ];

    // Pengajuan dimiliki oleh satu User (Pemohon)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Riwayat log untuk pengajuan ini.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(PengajuanLog::class);
    }
}