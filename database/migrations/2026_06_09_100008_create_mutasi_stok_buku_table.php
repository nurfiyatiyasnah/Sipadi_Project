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
            $table->id('id_mutasi_stok_buku');
            $table->unsignedBigInteger('id_buku');
            $table->unsignedBigInteger('id_petugas')->nullable();
            $table->string('jenis_mutasi', 30)->nullable();
            $table->integer('jumlah')->nullable();
            $table->integer('stok_total_sebelum')->nullable();
            $table->integer('stok_total_sesudah')->nullable();
            $table->integer('stok_tersedia_sebelum')->nullable();
            $table->integer('stok_tersedia_sesudah')->nullable();

            $table->foreign('id_buku')->references('id_buku')->on('buku');
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas');
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
