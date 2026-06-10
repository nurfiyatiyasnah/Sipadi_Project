<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEKartuAnggotaTable extends Migration
{
    public function up(): void
    {
        Schema::create('e_kartu_anggota', function (Blueprint $table) {
            $table->bigInteger('id_e_kartu_anggota')->primary();
            $table->bigInteger('id_anggota')->nullable();
            $table->bigInteger('no_anggota')->nullable();
            $table->string('kalangan', 30)->nullable();
            $table->bigInteger('barcode')->nullable();
            $table->date('masa_berlaku')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('update_at')->nullable();
            $table->unique('no_anggota');
            $table->unique('id_anggota');
            $table->foreign('id_anggota')
                ->references('id_anggota')
                ->on('anggota')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('e_kartu_anggota');
    }
}
