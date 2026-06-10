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
        Schema::create('mutasi_stok_buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buku_id')->constrained('buku')->cascadeOnDelete();
            $table->enum('jenis_mutasi', ['Masuk', 'Keluar', 'Hilang', 'Rusak']);
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->foreignId('petugas_id')->nullable()->constrained('petugas')->nullOnDelete();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_stok_buku');
    }
};
