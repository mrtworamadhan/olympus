<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Category;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'darussalam')->first();

        $sportId = DB::table('sports')->insertGetId([
            'name' => 'Futsal',
            'slug'=> 'futsal',
            'scoring_type' => 'goal_based',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $event = Event::create([
            'tenant_id' => $tenant->id,
            'name' => 'Darussalam Cup 2025',
            'slug' => 'darussalamcup2025',
            'start_date' => now()->addDays(14),
            'end_date' => now()->addDays(21),
            // 'location' => 'GOR Darussalam', // HAPUS: Tidak ada di struktur baru
            
            'public_description' => 'Turnamen Futsal SD & SMP Terbesar se-Jawa Barat.',
            'banner_image' => null,
            'logo' => null,
            
            'is_active' => true, // tinyint(1)
            'is_registration_open' => true, // tinyint(1)
            'primary_color' => '#007a4bff',
        ]);

        // Config Rules (JSON)
        $rules = [
            'max_fouls' => 5,
            'points_win' => 3,
            'points_draw' => 1,
            'points_loss' => 0,
            'is_stop_clock' => false,
            'period_duration' => 15
        ];

        // 3. Kategori U-12 (SD)
        Category::create([
            'tenant_id' => $tenant->id,
            'event_id' => $event->id,
            'sport_id' => $sportId,
            'name' => 'U-12 (SD)',
            'min_date_of_birth' => '2012-01-01',
            'max_date_of_birth' => '2014-12-31',
            'rules_settings' => $rules,
            'status' => 'registration',
            'format_type' => 'hybrid',
            'total_groups' => 4,
            'teams_passing_per_group' => 2,
            'knockout_stage_type' => 'quarter_final',
        ]);

        // 4. Kategori U-15 (SMP)
        Category::create([
            'tenant_id' => $tenant->id,
            'event_id' => $event->id,
            'sport_id' => $sportId,
            'name' => 'U-15 (SMP)',
            'min_date_of_birth' => '2009-01-01',
            'max_date_of_birth' => '2011-12-31',
            'rules_settings' => $rules,
            'status' => 'registration',
            'format_type' => 'hybrid',
            'total_groups' => 4,
            'teams_passing_per_group' => 2,
            'knockout_stage_type' => 'quarter_final',
        ]);
    }
}