<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_masuk_id',
        'pengirim_id',
        'penerima_id',
        'instruksi',
        'catatan_tambahan',
        'batas_waktu',
        'status',
        'laporan_penyelesaian',
    ];

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class);
    }

    // Siapa yang mengirim disposisi (Ka. Unit / Kasubbag)
    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    // Siapa yang menerima (Kasubbag / Staff)
    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }
}