<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kepala Unit
        User::updateOrCreate(
            ['email' => 'kaunit@uppd.com'],
            [
                'name' => 'Bapak Kepala Unit',
                'password' => Hash::make('password'),
                'role' => 'kepala_unit',
                'nik' => '111111',
                'no_hp' => '08111111111'
            ]
        );

        // 2. Kasubbag
        User::updateOrCreate(
            ['email' => 'kasubbag@uppd.com'],
            [
                'name' => 'Ibu Kasubbag TU',
                'password' => Hash::make('password'),
                'role' => 'kasubbag',
                'nik' => '222222',
                'no_hp' => '08222222222'
            ]
        );

        // 3. Staff
        User::updateOrCreate(
            ['email' => 'staff@uppd.com'],
            [
                'name' => 'Staff Administrasi',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'nik' => '333333',
                'no_hp' => '08333333333'
            ]
        );

        // 4. Admin
        User::updateOrCreate(
            ['email' => 'admin@uppd.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'nik' => '444444',
                'no_hp' => '08444444444'
            ]
        );
    }
}
