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
        Schema::table('eksemplar_buku', function (Blueprint $table) {
            $table->string('sumber_perolehan', 100)->nullable()->after('lokasi_rak');
            $table->text('catatan')->nullable()->after('sumber_perolehan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eksemplar_buku', function (Blueprint $table) {
            $table->dropColumn(['sumber_perolehan', 'catatan']);
        });
    }
};
