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
        // 1. Tabel Master Officials (Wasit/Operator)
        Schema::create('officials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete(); // Multi-tenant support
            $table->string('name');
            $table->string('role'); // 'referee', 'operator', 'judge', etc.
            $table->string('license_level')->nullable(); // Opsional: Lisensi C1, C2, dll
            $table->timestamps();
        });

        // 2. Tambah Kolom di Tabel Games (Untuk Assign Siapa Wasitnya)
        Schema::table('game_matches', function (Blueprint $table) {
            $table->foreignId('referee_id')->nullable()->constrained('officials')->nullOnDelete();
            $table->foreignId('operator_id')->nullable()->constrained('officials')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officials');

        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign(['referee_id']);
            $table->dropForeign(['operator_id']);
            $table->dropColumn('referee_id');
            $table->dropColumn('operator_id');
        });
    }
};
