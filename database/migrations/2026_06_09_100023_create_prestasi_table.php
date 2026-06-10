<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestasiTable extends Migration
{
    public function up(): void
    {
        Schema::create('prestasi', function (Blueprint $table) {
            $table->bigInteger('id_prestasi')->primary();
            $table->string('judul_prestasi', 150)->nullable();
            $table->string('slug', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('tingkat_prestasi', 50)->nullable();
            $table->string('penyelenggra', 150)->nullable();
            $table->date('tanggal_prestasi')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->string('file_lampiran', 255)->nullable();
            $table->string('status_prestasi', 20)->nullable();
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
        Schema::dropIfExists('prestasi');
    }
}
