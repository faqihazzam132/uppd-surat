<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_surat',
        'tanggal_surat',
        'tujuan',
        'perihal',
        'file_draft',
        'file_final',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function arsip()
    {
        return $this->morphOne(Arsip::class, 'surat');
    }
}