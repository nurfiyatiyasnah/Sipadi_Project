<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotaTable extends Migration
{
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->bigInteger('id_anggota')->primary();
            $table->bigInteger('id_user')->nullable();
            $table->string('no_anggota', 30)->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('nama_lengkap', 100)->nullable();
            $table->string('jenis_kelamin', 10)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto', 255)->nullable();
            $table->date('tanggal_daftar')->nullable();
            $table->string('status_anggota', 20)->nullable();
            $table->text('alasan_nonaktif')->nullable();
            $table->timestamp('created_at ', 0)->nullable();
            $table->timestamp('updated_at ', 0)->nullable();
            $table->unique('no_anggota');
            $table->unique('id_user');
            $table->unique('nik');
            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
}
