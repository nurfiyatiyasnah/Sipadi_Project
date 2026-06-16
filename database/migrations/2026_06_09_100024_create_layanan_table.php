<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLayananTable extends Migration
{
    public function up(): void
    {
        Schema::create('layanan', function (Blueprint $table) {

            $table->id('id_layanan');
            $table->string('nama_layanan', 150);

            $table->string('slug', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('persyaratan')->nullable();
            $table->text('prosedur')->nullable();
            $table->string('jam_layanan', 100)->nullable();
            $table->string('biaya', 100)->nullable();
            $table->string('kontak_layanan', 100)->nullable();
            $table->string('gambar', 255)->nullable();
            $table->string('status_layanan', 20)->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id_petugas')->on('petugas');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanan');
    }
}
