<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutasiStokBukuTable extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi_stok_buku', function (Blueprint $table) {
            $table->bigInteger('id_mutasi_stok_buku')->primary();
            $table->bigInteger('id_buku')->nullable();
            $table->bigInteger('id_petugas')->nullable();
            $table->string('jenis_mutasi', 30)->nullable();
            $table->integer('jumlah')->nullable();
            $table->integer('stok_total_sebelum')->nullable();
            $table->integer('stok_total_sesudah')->nullable();
            $table->integer('stok_tersedia_sebelum')->nullable();
            $table->integer('stok_tersedia_sesudah')->nullable();
            $table->foreign('id_buku')
                ->references('id_buku')
                ->on('buku')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('id_petugas')
                ->references('id_petugas')
                ->on('petugas')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_stok_buku');
    }
}
