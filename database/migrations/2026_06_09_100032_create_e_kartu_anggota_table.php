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
        Schema::create('e_kartu_anggota', function (Blueprint $table) {
            $table->id('id_e_kartu_anggota');
            $table->unsignedBigInteger('id_anggota')->unique();
            $table->unsignedBigInteger('no_anggota')->unique();
            $table->string('kalangan', 30)->nullable();
            $table->unsignedBigInteger('barcode')->nullable();
            $table->date('masa_berlaku')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('update_at')->nullable();

            $table->foreign('id_anggota')->references('id_anggota')->on('anggota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_kartu_anggota');
    }
};
