<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder secara berurutan karena ada ketergantungan (User -> Tenant -> Official)
        $this->call([
            UserSeeder::class,      // 1. Buat User (Superadmin, Admin, Manager, Operator, Wasit)
            TenantSeeder::class,    // 2. Buat Tenant & Link Admin ke Tenant
            OfficialSeeder::class,  // 3. Daftarkan Operator & Wasit ke tabel Officials & Link ke Tenant
            VenueSeeder::class,     // 4. Venue (BARU)
            EventSeeder::class,     // 5. Event & Kategori
            TeamManagerSeeder::class,
            CompetitorSeeder::class,
        ]);
    }
}
