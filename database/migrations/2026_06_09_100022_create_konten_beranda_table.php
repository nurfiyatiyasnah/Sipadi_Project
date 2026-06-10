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
        Schema::create('konten_beranda', function (Blueprint $table) {
            $table->id('id_konten_beranda');
            $table->string('judul', 150)->nullable();
            $table->text('isi')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->integer('urutan')->default(0);
            $table->string('status_konten', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konten_beranda');
    }
};
