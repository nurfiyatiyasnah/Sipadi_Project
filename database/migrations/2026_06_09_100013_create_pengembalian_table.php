<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengembalianTable extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {

            $table->id('id_pengembalian');
            $table->unsignedBigInteger('id_peminjaman')->unique();
            $table->unsignedBigInteger('id_petugas')->nullable();
            $table->timestamp('tanggal_pengembalian')->nullable();
            $table->integer('total_hari_terlambat')->default(0);
            $table->string('status_pengembalian', 20)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_peminjaman')->references('id_peminjaman')->on('peminjaman');
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
}
