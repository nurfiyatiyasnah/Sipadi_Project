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
        Schema::create('prestasi', function (Blueprint $table) {
            $table->id('id_prestasi');
            $table->string('judul_prestasi', 150);
            $table->string('slug', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('tingkat_prestasi', 50)->nullable();
            $table->string('penyelenggra', 150)->nullable();
            $table->date('tanggal_prestasi')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->string('file_lampiran', 255)->nullable();
            $table->string('status_prestasi', 20)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id_petugas')->on('petugas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi');
    }
};
