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
        Schema::table('fasilitas', function (Blueprint $table) {
            $table->string('kategori')->nullable();
            $table->string('satuan_kapasitas')->nullable(); // Orang, Unit, dsb.
            $table->json('kelengkapan')->nullable(); // AC, Wi-Fi, dll
            $table->boolean('tampilkan_publik')->default(true);
            $table->boolean('aktifkan_reservasi')->default(false);
            $table->string('metode_peminjaman')->nullable();
            $table->integer('durasi_maksimal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fasilitas', function (Blueprint $table) {
            $table->dropColumn([
                'kategori',
                'satuan_kapasitas',
                'kelengkapan',
                'tampilkan_publik',
                'aktifkan_reservasi',
                'metode_peminjaman',
                'durasi_maksimal'
            ]);
        });
    }
};
