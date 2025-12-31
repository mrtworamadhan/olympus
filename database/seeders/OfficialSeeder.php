<?php

namespace Database\Seeders;

use App\Models\Official;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class OfficialSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil Tenant
        $tenant = Tenant::where('slug', 'darussalam')->first();

        if (!$tenant) {
            $this->command->error('❌ Tenant not found! Run TenantSeeder first.');
            return;
        }

        // 2. Setup Operator Match
        $opUser = User::where('email', 'operator@darussalam.com')->first();
        if ($opUser) {
            Official::create([
                'tenant_id' => $tenant->id,
                'user_id'   => $opUser->id,
                'name'      => $opUser->name,
                'role'      => 'operator', // Role ini harus sesuai dengan enum di database/kode
                'license_level' => 'National', // Dummy data
            ]);
            
            // PENTING: Operator harus terikat dengan tenant agar bisa login
            // Cek dulu apakah sudah terikat, kalau belum baru attach
            if (!$opUser->tenants()->where('tenant_id', $tenant->id)->exists()) {
                $opUser->tenants()->attach($tenant->id);
            }
        }

        // 3. Setup Referee Match (Wasit)
        $refUser = User::where('email', 'referee@darussalam.com')->first();
        if ($refUser) {
            Official::create([
                'tenant_id' => $tenant->id,
                'user_id'   => $refUser->id,
                'name'      => $refUser->name,
                'role'      => 'referee', // Role ini harus sesuai
                'license_level' => 'FIFA', // Dummy data
            ]);
            
            // Wasit juga perlu akses login ke tenant ini
            if (!$refUser->tenants()->where('tenant_id', $tenant->id)->exists()) {
                $refUser->tenants()->attach($tenant->id);
            }
        }

        $this->command->info('✅ Officials (Operator & Referee) Registered Successfully!');
    }
}