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
        Schema::create('ajukan_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->date('tanggal_pengajuan');
            $table->enum('status_pengajuan', ['Menunggu', 'Disetujui', 'Ditolak', 'Dibatalkan'])->default('Menunggu')->index();
            $table->text('catatan_anggota')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajukan_peminjaman');
    }
};
