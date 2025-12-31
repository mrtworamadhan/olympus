<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil User Admin yang sudah dibuat di UserSeeder
        $adminUser = User::where('email', 'admin@darussalam.com')->first();

        if (!$adminUser) {
            $this->command->error('❌ Admin user not found! Run UserSeeder first.');
            return;
        }

        // 2. Buat Tenant (EO Profile)
        $tenant = Tenant::create([
            'name' => 'Darussalam Event Organizer',
            'slug' => 'darussalam',
            'logo' => null, // Bisa diisi path gambar nanti
            'primary_color' => '#0ea5e9', // Warna tema dashboard (Cyan)
        ]);

        // 3. Hubungkan Admin ke Tenant (Isi tabel pivot tenant_user)
        // Pastikan di model User ada method: public function tenants() { return $this->belongsToMany(Tenant::class); }
        $adminUser->tenants()->attach($tenant->id);

        $this->command->info('✅ Tenant Created & Admin Linked!');
    }
}