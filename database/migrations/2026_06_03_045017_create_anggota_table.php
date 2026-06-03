<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id('id_anggota'); // BIGINT PK 
            $table->foreignId('id_user')->unique()->constrained('users', 'id_user')->onDelete('cascade'); // Relasi 1 - 1 dengan users (id_user dibuat UNIQUE)
            $table->string('no_anggota', 50)->unique();
            $table->string('nik', 20);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->date('tanggal_daftar');
            $table->enum('status_anggota', ['menunggu_verifikasi', 'aktif', 'nonaktif', 'ditolak'])->default('menunggu_verifikasi');
            $table->text('alasan_nonaktif')->nullable();
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};