<?php

use App\Models\Pengajuan;
use App\Models\User;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking recent Pengajuan records...\n";
$latest = Pengajuan::latest()->take(5)->get();

if ($latest->isEmpty()) {
    echo "No records found.\n";
} else {
    foreach ($latest as $p) {
        $user = User::find($p->user_id);
        echo "ID: {$p->id} | No: {$p->no_registrasi} | Status: {$p->status} | UserID: {$p->user_id} (" . ($user ? $user->name : 'MISSING') . ") | Created: {$p->created_at}\n";
    }
}

echo "\nChecking Admin Query Simulation:\n";
$query = Pengajuan::with(['user'])->orderByDesc('created_at')->limit(5)->get();
echo "Admin Query returned " . $query->count() . " records.\n";
