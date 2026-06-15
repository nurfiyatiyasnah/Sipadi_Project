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
            $table->id('id_detail_pengembalian');
            $table->unsignedBigInteger('id_pengembalian');
            $table->unsignedBigInteger('id_detail_peminjaman');
            $table->integer('jumlah_dikembalikan')->nullable();
            $table->string('kondisi_buku', 30)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_pengembalian')->references('id_pengembalian')->on('pengembalian');
            $table->foreign('id_detail_peminjaman')->references('id_detail_peminjaman')->on('detail_peminjaman');
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
