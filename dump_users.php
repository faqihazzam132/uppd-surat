<?php

use App\Models\User;

$users = User::all();
foreach ($users as $user) {
    echo "ID: " . $user->id . " | Name: " . $user->name . " | Role: " . $user->role . "\n";
}
