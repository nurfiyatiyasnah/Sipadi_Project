<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetugasTable extends Migration
{
    public function up(): void
    {
        Schema::create('petugas', function (Blueprint $table) {
            $table->bigInteger('id_petugas')->primary();
            $table->bigInteger('id_user')->nullable();
            $table->string('nama_petugas', 100)->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->unique('id_user');
            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
}
