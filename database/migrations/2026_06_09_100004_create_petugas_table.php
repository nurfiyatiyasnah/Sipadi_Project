<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetugasTable extends Migration
{
    public function up(): void
    {
        Schema::create('petugas', function (Blueprint $table) {

            $table->id('id_petugas');
            $table->unsignedBigInteger('id_user')->unique();
            $table->string('nama_petugas', 100);
            $table->string('jabatan', 100)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
}
