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
        Schema::create('riwayat_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->foreignId('peminjaman_id')->nullable()->constrained('peminjaman')->nullOnDelete();
            $table->foreignId('pengembalian_id')->nullable()->constrained('pengembalian')->nullOnDelete();
            $table->enum('jenis_aktivitas', ['Peminjaman', 'Pengembalian', 'Denda', 'Lainnya']);
            $table->text('deskripsi')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_anggota');
    }
};
