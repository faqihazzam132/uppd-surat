<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_klasifikasi',
        'lokasi_arsip',
        'tanggal_arsip',
        'file_arsip',
        'surat_type',
        'surat_id',
    ];

    /**
     * Get the parent surat model (SuratMasuk or SuratKeluar).
     */
    public function surat()
    {
        return $this->morphTo();
    }
}
