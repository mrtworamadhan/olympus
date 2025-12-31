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
        Schema::table('game_matches', function (Blueprint $table) {
            // Tambahkan kolom event_id setelah tenant_id (atau id)
            // Dibuat nullable dulu biar aman untuk data lama
            $table->foreignId('event_id')
                  ->nullable() 
                  ->after('tenant_id') // Posisikan setelah tenant_id biar rapi
                  ->constrained('events')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_matches', function (Blueprint $table) {
            // Hapus foreign key dulu baru kolomnya
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }
};