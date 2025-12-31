<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Pemain
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competitor_id')->constrained('competitors')->cascadeOnDelete(); // Link ke Tim
            $table->string('name');
            $table->integer('number')->nullable(); // Nomor Punggung
            $table->string('position')->nullable(); // GK, DF, MF, FW
            $table->string('photo')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Event Pertandingan (Gol, Kartu, dll)
        Schema::create('match_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_match_id')->constrained('game_matches')->cascadeOnDelete();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete(); // Siapa pelakunya
            $table->foreignId('competitor_id')->constrained('competitors'); // Tim mana (biar cepat querynya)
            // Tipe Event: 'goal', 'yellow_card', 'red_card', 'own_goal'
            $table->string('event_type'); 
            $table->integer('minute')->default(0); // Menit kejadian
            $table->timestamps();
        });
    }
};
