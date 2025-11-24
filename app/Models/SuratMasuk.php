<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_agenda',
        'no_surat',
        'tanggal_surat',
        'tanggal_diterima',
        'pengirim',
        'perihal',
        'sifat',
        'file_path',
        'status',
        'user_id',
    ];

    // Relasi: Surat Masuk dicatat oleh User (Staff)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu Surat Masuk bisa punya banyak Disposisi (berjenjang)
    public function disposisis()
    {
        return $this->hasMany(Disposisi::class);
    }
}