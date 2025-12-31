<?php

namespace Database\Seeders;

use App\Models\Venue;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'darussalam')->first();

        if (!$tenant) {
            $this->command->error('❌ Tenant not found! Run TenantSeeder first.');
            return;
        }

        $venues = [
            [
                'name' => 'GOR Utama Darussalam',
                'location' => 'Jl. Kampus No. 1, Arena A',
            ],
            [
                'name' => 'Lapangan Futsal Outdoor',
                'location' => 'Jl. Kampus No. 1, Arena B',
            ],
            [
                'name' => 'Hall Serbaguna',
                'location' => 'Gedung Olahraga Lt. 2',
            ],
        ];

        foreach ($venues as $v) {
            Venue::create([
                'tenant_id' => $tenant->id,
                'name' => $v['name'],
                'location' => $v['location'],
            ]);
        }

        $this->command->info('✅ Venues Created Successfully!');
    }
}