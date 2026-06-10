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
        Schema::create('log_pengiriman_notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notifikasi_id')->constrained('notifikasi')->cascadeOnDelete();
            $table->enum('metode', ['Email', 'WhatsApp', 'Sistem']);
            $table->enum('status', ['Terkirim', 'Gagal', 'Pending'])->default('Pending');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_pengiriman_notifikasi');
    }
};
