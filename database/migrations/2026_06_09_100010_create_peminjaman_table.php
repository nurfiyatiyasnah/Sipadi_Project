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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ajukan_peminjaman_id')->constrained('ajukan_peminjaman')->cascadeOnDelete();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_harus_kembali')->index();
            $table->enum('status_peminjaman', ['Aktif', 'Selesai', 'Terlambat'])->default('Aktif')->index();
            $table->foreignId('petugas_id')->nullable()->constrained('petugas')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
