<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_matches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('venue_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('home_competitor_id')->nullable()->constrained('competitors')->nullOnDelete();
            $table->foreignId('away_competitor_id')->nullable()->constrained('competitors')->nullOnDelete();

            $table->dateTime('scheduled_at'); 
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();

            $table->integer('home_score')->default(0);
            $table->integer('away_score')->default(0);

            $table->enum('status', [
                'scheduled', 'processing', 'live', 'break', 
                'finished', 'walkover', 'cancelled'
            ])->default('scheduled');

            $table->json('meta_data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_matches');
    }
};
