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
        Schema::create('arsip_aduan', function (Blueprint $table) {
            $table->id('id_arsip_aduan');
            $table->unsignedBigInteger('id_aduan')->unique();
            $table->unsignedBigInteger('diarsipkan_oleh')->nullable();
            $table->text('alasan_diarsipkan')->nullable();
            $table->timestamp('diarsipkan_pada')->nullable();
            $table->timestamps();

            $table->foreign('id_aduan')->references('id_aduan')->on('aduan');
            $table->foreign('diarsipkan_oleh')->references('id_petugas')->on('petugas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_aduan');
    }
};
