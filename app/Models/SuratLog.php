<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_type',
        'surat_id',
        'user_id',
        'action',
        'description',
    ];

    public function surat()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
