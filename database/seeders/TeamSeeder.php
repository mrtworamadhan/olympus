<?php

namespace Database\Seeders;

use App\Models\Competitor;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $eventId = 1; 

        $teams = [
            'SDN Ciomas', 'SD Tarakanita', 'SD Al-Azhar', 'SD Penabur',
            'SDN Kebon Jeruk', 'SD Pelita Harapan', 'SD Menteng 01',
            'SD Marsudirini', 'SD Global Mandiri', 'SD Bina Bangsa',
            'SDN Ragunan', 'SD Cikal', 'SD High Scope', 'SD Alam Sentosa',
            'SD Tarakanita', 'SD Seroja'
        ];

        foreach ($teams as $name) {
            Competitor::create([
                'event_id' => $eventId,
                'name' => $name,
                'short_name' => strtoupper(substr(str_replace('SD ', '', $name), 0, 3)),
                'group_name' => null
            ]);
        }
    }
}