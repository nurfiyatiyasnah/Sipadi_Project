<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEksemplarBukuTable extends Migration
{
    public function up(): void
    {
        Schema::create('eksemplar_buku', function (Blueprint $table) {
            $table->bigInteger('id_eksemplar_buku')->primary();
            $table->bigInteger('id_buku')->nullable();
            $table->string('kode_eksemplar', 50)->nullable();
            $table->string('status_eksemplar', 20)->nullable();
            $table->string('kondisi_eksemplar', 20)->nullable();
            $table->string('lokasi_rak', 100)->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign('id_buku')
                ->references('id_buku')
                ->on('buku')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eksemplar_buku');
    }
}
