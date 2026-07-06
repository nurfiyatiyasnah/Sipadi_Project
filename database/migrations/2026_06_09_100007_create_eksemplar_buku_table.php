<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEksemplarBukuTable extends Migration
{
    public function up(): void
    {
        Schema::create('eksemplar_buku', function (Blueprint $table) {

            $table->id('id_eksemplar_buku');
            $table->unsignedBigInteger('id_buku');
            $table->string('kode_eksemplar', 50);

            $table->string('status_eksemplar', 20)->nullable();
            $table->string('kondisi_eksemplar', 20)->nullable();
            $table->string('lokasi_rak', 100)->nullable();
            $table->date('tanggal_masuk')->nullable();

            $table->timestamps();

            $table->foreign('id_buku')->references('id_buku')->on('buku');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eksemplar_buku');
    }
}
