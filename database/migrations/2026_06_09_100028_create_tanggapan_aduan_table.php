<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanggapan_aduan', function (Blueprint $table) {
            $table->id('id_tanggapan');

            $table->unsignedBigInteger('id_aduan');
            $table->unsignedBigInteger('id_petugas')->nullable();
            $table->text('isi_tanggapan')->nullable();

            $table->string('status_setelah_respon', 20)->nullable();
            $table->timestamp('ditanggapi_pada')->nullable();
            $table->timestamps();

            $table->foreign('id_aduan')->references('id_aduan')->on('aduan');
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanggapan_aduan');
    }
};
