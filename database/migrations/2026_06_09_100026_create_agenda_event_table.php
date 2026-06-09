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
        Schema::create('agenda_event', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->dateTime('tanggal_mulai')->index();
            $table->dateTime('tanggal_selesai')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('gambar')->nullable();
            $table->enum('status', ['Akan Datang', 'Berlangsung', 'Selesai', 'Dibatalkan'])->default('Akan Datang')->index();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_event');
    }
};
