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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();

            $table->foreignId('sport_id')->constrained();
            
            $table->string('name');

            $table->date('min_date_of_birth')->nullable(); 

            $table->date('max_date_of_birth')->nullable();
            
            $table->json('rules_settings')->nullable(); 
            
            $table->enum('status', ['draft', 'registration', 'drawing', 'ongoing', 'completed'])->default('draft');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
