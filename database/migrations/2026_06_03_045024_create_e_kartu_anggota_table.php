<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('e_kartu_anggota', function (Blueprint $table) {
            $table->id('id_kartu'); // BIGINT PK
            $table->foreignId('id_anggota')->constrained('anggota', 'id_anggota')->onDelete('cascade'); // Relasi 1 - 1 dengan anggota
            $table->string('nomor_kartu', 50)->unique();
            $table->string('qr_code', 255);
            $table->string('file_kartu', 255);
            $table->enum('status_verifikasi', ['belum_verifikasi', 'terverifikasi', 'ditolak'])->default('belum_verifikasi');
            $table->foreignId('verified_by')->nullable()->constrained('users', 'id_user')->onDelete('set null'); // Relasi 1 - n dengan users (verified_by bisa NULL)
            $table->date('tanggal_terbit')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('e_kartu_anggota');
    }
};