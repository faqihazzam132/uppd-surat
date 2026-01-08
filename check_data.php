<?php

use App\Models\Pengajuan;
use App\Models\SuratMasuk;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$no_registrasi = 'REG-20260108-8A5'; // From the error screenshot
$pengajuan = Pengajuan::where('no_registrasi', $no_registrasi)->first();

if (!$pengajuan) {
    echo "Pengajuan $no_registrasi not found.\n";
    // Try to find the latest one just in case
    $pengajuan = Pengajuan::latest()->first();
    echo "Checking latest pengajuan: " . ($pengajuan ? $pengajuan->no_registrasi : 'None') . "\n";
}

if ($pengajuan) {
    echo "Pengajuan Status: " . $pengajuan->status . "\n";
    
    $suratMasuk = SuratMasuk::where('no_surat', $pengajuan->no_registrasi)->first();
    
    if ($suratMasuk) {
        echo "SuratMasuk Found! ID: " . $suratMasuk->id . "\n";
        echo "Posisi: " . $suratMasuk->posisi . "\n";
        echo "Status: " . $suratMasuk->status . "\n";
    } else {
        echo "SuratMasuk MISSING.\n";
    }
}
