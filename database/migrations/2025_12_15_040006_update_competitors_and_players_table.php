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
        Schema::table('competitors', function (Blueprint $table) {

            $table->string('status')->default('pending');
            
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::table('players', function (Blueprint $table) {

            $table->date('date_of_birth')->nullable();

            $table->string('identity_card_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competitors', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('category_id');
        });
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('date_of_birth');
            $table->dropColumn('identity_card_file');
        });
        
    }
};
