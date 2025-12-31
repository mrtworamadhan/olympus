<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin (Global)
        User::create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'phone_number' => '081234567890',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        // 2. Admin Tenant (EO Owner)
        User::create([
            'name' => 'Admin Darussalam',
            'email' => 'admin@darussalam.com',
            'phone_number' => '08987654321',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 3. Manager Team (Contoh Akun Manager Umum)
        User::create([
            'name' => 'Manager Team General',
            'email' => 'manager@darussalam.com',
            'phone_number' => '08111111111',
            'password' => Hash::make('password'),
            'role' => 'team',
        ]);

        // 4. Operator Match (Akan dilink ke tabel officials nanti)
        User::create([
            'name' => 'Operator Match',
            'email' => 'operator@darussalam.com',
            'phone_number' => '08222222222',
            'password' => Hash::make('password'),
            'role' => 'official', // Role di user tetap 'official'
        ]);

        // 5. Referee Match (Akan dilink ke tabel officials nanti)
        User::create([
            'name' => 'Referee Match',
            'email' => 'referee@darussalam.com', // Typo fix: refree -> referee (biar standar inggris)
            'phone_number' => '08333333333',
            'password' => Hash::make('password'),
            'role' => 'official', // Role di user tetap 'official'
        ]);
    }
}