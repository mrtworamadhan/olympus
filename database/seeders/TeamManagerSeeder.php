<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Event;
use App\Models\Category;
use App\Models\MasterClub;
use App\Models\MasterPlayer;
use App\Models\Competitor;
use App\Models\Player;
use Illuminate\Database\Seeder;

class TeamManagerSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil Data Pendukung
        $tenant = Tenant::where('slug', 'darussalam')->first();
        $event = Event::where('slug', 'darussalamcup2025')->first();
        
        // Pastikan kategori ada (ambil dari EventSeeder)
        $catSD = Category::where('event_id', $event->id)->where('name', 'LIKE', '%U-12%')->first();
        $catSMP = Category::where('event_id', $event->id)->where('name', 'LIKE', '%U-15%')->first();

        // 2. Ambil Akun Manager General (Pastikan UserSeeder sudah jalan)
        $manager = User::where('email', 'manager@darussalam.com')->first();

        if (!$manager || !$tenant || !$event || !$catSD || !$catSMP) {
            $this->command->error('❌ Data pendukung tidak lengkap. Jalankan UserSeeder, TenantSeeder, & EventSeeder dulu.');
            return;
        }

        // Link Manager ke Tenant
        if (!$manager->tenants()->where('tenant_id', $tenant->id)->exists()) {
            $manager->tenants()->attach($tenant->id);
        }

        // --- SKENARIO: Manager punya 2 Tim (1 SD, 1 SMP) ---
        
        $myTeams = [
            [
                'club_name' => 'SD Generasi Juara',
                'category' => $catSD,
                'birth_year' => '2012',
                'short' => 'GEN',
            ],
            [
                'club_name' => 'SMP Bintang Masa Depan',
                'category' => $catSMP,
                'birth_year' => '2009',
                'short' => 'BMD',
            ]
        ];

        foreach ($myTeams as $teamData) {
            
            // A. BUAT MASTER CLUB (Database Tim Milik Manager)
            $masterClub = MasterClub::firstOrCreate(
                ['user_id' => $manager->id, 'name' => $teamData['club_name']],
                ['logo' => null]
            );

            $this->command->info("✅ Master Club Siap: " . $masterClub->name);

            // B. DAFTARKAN SEBAGAI COMPETITOR (Masuk Turnamen)
            $competitor = Competitor::create([
                'tenant_id'       => $tenant->id,
                'event_id'        => $event->id,
                'category_id'     => $teamData['category']->id,
                'user_id'         => $manager->id,
                'master_club_id'  => $masterClub->id,
                'name'            => $masterClub->name,
                'short_name'      => $teamData['short'],
                'team_manager'    => $manager->name, // Nama Manager (String)
                'status'          => 'approved',     // Langsung Approve
                'group_name'      => null,           // Belum drawing
                'logo'            => null,
            ]);

            // C. BUAT 10 PEMAIN (MASTER & ROSTER)
            for ($i = 1; $i <= 10; $i++) {
                
                // 1. Simpan di Master Player
                $dob = $teamData['birth_year'] . '-05-' . sprintf('%02d', $i);
                
                $masterPlayer = MasterPlayer::create([
                    'user_id'        => $manager->id,
                    'master_club_id' => $masterClub->id,
                    'name'           => 'Pemain ' . $teamData['short'] . ' ' . $i,
                    'date_of_birth'  => $dob,
                    'position'       => $i === 1 ? 'GK' : 'Player',
                    'number'         => $i,
                    'photo'          => null,
                    'identity_card_file' => null,
                ]);

                // 2. Simpan di Player Turnamen (Sesuai kolom tabel player kamu)
                Player::create([
                    'competitor_id'    => $competitor->id,
                    // 'master_player_id' => $masterPlayer->id, // Relasi ke Master
                    'name'             => $masterPlayer->name,
                    'number'           => $masterPlayer->number,
                    'position'         => $masterPlayer->position,
                    'date_of_birth'    => $masterPlayer->date_of_birth, // Copy data
                    'identity_card_file' => null,
                    'photo'            => null,
                ]);
            }
        }

        $this->command->info('🚀 Sukses! Manager General punya 2 Tim (SD & SMP) + 20 Pemain di tabel Player.');
    }
}