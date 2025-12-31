<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Competitor;
use App\Models\MasterClub;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompetitorSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'darussalam')->first();
        
        // Ambil Kategori
        $catSD = Category::where('name', 'LIKE', '%U-12%')->first();
        $catSMP = Category::where('name', 'LIKE', '%U-15%')->first();

        // 1. Array 15 Nama Sekolah SD (Sisa)
        $sekolahSD = [
            'SDN 01 Pagi', 'SD Islam Al-Azhar', 'SDIT Nurul Fikri', 'SD Santa Ursula',
            'SDN Cilandak 05', 'SD Global Mandiri', 'SD Pelita Harapan', 'SDN Ragunan 12',
            'SD Tarakanita', 'SD Al-Izhar', 'SD Bakti Mulya 400', 'SDN Pondok Indah',
            'SD Cikal', 'SD HighScope', 'SD Mentari'
        ];

        foreach ($sekolahSD as $i => $namaSekolah) {
            $this->createTeamFlow($tenant, $catSD, $namaSekolah, 'sd_mgr_'.$i);
        }

        // 2. Array 15 Nama Sekolah SMP (Sisa)
        $sekolahSMP = [
            'SMPN 1 Jakarta', 'SMPN 19 Jakarta', 'SMPN 115 Jakarta', 'SMPN 41 Jakarta',
            'SMP Al-Azhar 1', 'SMP Labschool', 'SMP Tarakanita 1', 'SMP Kanisius',
            'SMP Santa Laurensia', 'SMP Penabur', 'SMP Global Jaya', 'SMP Binus',
            'SMP Cikal', 'SMP Mentari', 'SMP HighScope'
        ];

        foreach ($sekolahSMP as $i => $namaSekolah) {
            $this->createTeamFlow($tenant, $catSMP, $namaSekolah, 'smp_mgr_'.$i);
        }
        
        $this->command->info('✅ Berhasil Menambahkan 30 Tim Baru (15 SD + 15 SMP). Total sekarang 16 per kategori.');
    }

    private function createTeamFlow($tenant, $category, $teamName, $emailPrefix)
    {
        // 1. Buat Manager
        $manager = User::create([
            'name' => 'Manager ' . $teamName,
            'email' => $emailPrefix . '@mail.com',
            'phone_number' => '08' . rand(1000000000, 9999999999),
            'password' => Hash::make('password'),
            'role' => 'team',
        ]);
        
        // Manager wajib attach ke Tenant
        $manager->tenants()->attach($tenant->id);

        // 2. Buat Master Club
        $masterClub = MasterClub::create([
            'user_id' => $manager->id,
            'name' => $teamName,
            'logo' => null,
        ]);

        // 3. Daftar ke Event (Competitor)
        Competitor::create([
            'tenant_id'       => $tenant->id,
            'event_id'        => $category->event_id,
            'category_id'     => $category->id,
            'user_id'         => $manager->id,
            'master_club_id'  => $masterClub->id,
            'name'            => $masterClub->name,
            'short_name'      => strtoupper(substr(str_replace(['SD ', 'SMP ', ' '], '', $teamName), 0, 3)),
            'team_manager'    => $manager->name,
            'status'          => 'approved',
            'logo'            => null,
        ]);
    }
}