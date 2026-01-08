<?php

use Illuminate\Support\Facades\Schema;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (Schema::hasTable('sessions')) {
    echo "Table 'sessions' EXISTS.\n";
} else {
    echo "Table 'sessions' MISSING.\n";
}
