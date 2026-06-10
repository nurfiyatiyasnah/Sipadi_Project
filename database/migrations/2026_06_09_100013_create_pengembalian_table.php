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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->cascadeOnDelete();
            $table->date('tanggal_pengembalian');
            $table->foreignId('diterima_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('denda_keterlambatan', 12, 2)->default(0);
            $table->enum('status_pengembalian', ['Selesai', 'Sebagian', 'Bermasalah'])->default('Selesai')->index();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
