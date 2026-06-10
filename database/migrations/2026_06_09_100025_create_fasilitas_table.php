<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFasilitasTable extends Migration
{
    public function up(): void
    {
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->bigInteger('id_fasilitas')->primary();
            $table->string('nama_fasilitas', 150)->nullable();
            $table->string('slug', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('lokasi', 150)->nullable();
            $table->integer('jumlah_unit')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->string('status_fasilitas', 20)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign('created_by')
                ->references('id_petugas')
                ->on('petugas')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fasilitas');
    }
}
