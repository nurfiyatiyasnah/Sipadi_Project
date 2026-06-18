<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeterlambatanTable extends Migration
{
    public function up(): void
    {
        Schema::create('keterlambatan', function (Blueprint $table) {

            $table->id('id_keterlambatan');
            $table->unsignedBigInteger('id_peminjaman')->unique();
            $table->unsignedBigInteger('id_anggota');

            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->date('tanggal_dihitung')->nullable();
            $table->integer('hari_terlambat')->nullable();
            $table->string('status_perhitungan', 20)->nullable();

            $table->timestamps();

            $table->foreign('id_peminjaman')->references('id_peminjaman')->on('peminjaman');
            $table->foreign('id_anggota')->references('id_anggota')->on('anggota');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keterlambatan');
    }
}
