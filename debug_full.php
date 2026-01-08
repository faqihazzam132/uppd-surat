<?php

use App\Models\Pengajuan;
use App\Models\User;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$output = "";
echo "--- Recent Pengajuan (Limit 5) ---\n";
$output .= "--- Recent Pengajuan (Limit 5) ---\n";
$latest = Pengajuan::with('user')->latest()->take(5)->get();

if ($latest->isEmpty()) {
    $output .= "No Pengajuan found.\n";
} else {
    foreach ($latest as $p) {
        $userName = $p->user ? $p->user->name : 'UNKNOWN';
        $output .= "[{$p->id}] Reg: {$p->no_registrasi} | Status: {$p->status} | User: {$userName} ({$p->user_id})\n";
    }
}

echo "\n--- Users (Limit 5) ---\n";
$output .= "\n--- Users (Limit 5) ---\n";
$users = User::all();
foreach ($users as $u) {
    $output .= "[{$u->id}] {$u->name} | Role: {$u->role} | Email: {$u->email}\n";
}

file_put_contents('debug_result.txt', $output);
echo "Debug output written to debug_result.txt\n";
