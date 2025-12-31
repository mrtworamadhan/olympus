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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('format_type')->default('hybrid'); 

            $table->integer('total_groups')->default(4); 
            $table->integer('teams_passing_per_group')->default(2); 

            $table->enum('knockout_stage_type', [
                'round_of_32', 'round_of_16', 'quarter_final', 'semi_final', 'final'
            ])->default('quarter_final');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
};
