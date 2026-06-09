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
        Schema::create('detail_pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengembalian_id')->constrained('pengembalian')->cascadeOnDelete();
            $table->foreignId('detail_peminjaman_id')->constrained('detail_peminjaman')->cascadeOnDelete();
            $table->enum('kondisi_buku', ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Hilang']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengembalian');
    }
};
