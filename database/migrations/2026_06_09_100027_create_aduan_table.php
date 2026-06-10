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
        Schema::create('aduan', function (Blueprint $table) {
            $table->id('id_aduan');
            $table->string('kode_aduan', 30)->unique();
            $table->unsignedBigInteger('id_anggota');
            $table->string('subjek', 150);
            $table->text('isi_aduan')->nullable();
            $table->string('kategori_aduan', 30)->nullable();
            $table->string('lampiran', 255)->nullable();
            $table->string('status_aduan', 20)->nullable();
            $table->string('prioritas', 20)->nullable();
            $table->timestamps();

            $table->foreign('id_anggota')->references('id_anggota')->on('anggota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aduan');
    }
};
