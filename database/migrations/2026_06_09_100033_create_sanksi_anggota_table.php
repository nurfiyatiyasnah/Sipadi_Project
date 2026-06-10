<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSanksiAnggotaTable extends Migration
{
    public function up(): void
    {
        Schema::create('sanksi_anggota', function (Blueprint $table) {
            $table->bigInteger('id_sanksi_anggota')->primary();
            $table->bigInteger('id_anggota')->nullable();
            $table->bigInteger('id_peminjaman')->nullable();
            $table->bigInteger('id_keterlambatan')->nullable();
            $table->string('jenis_sanksi', 30)->nullable();
            $table->text('alasan')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('status_sanksi', 20)->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->unique('id_peminjaman');
            $table->unique('id_keterlambatan');
            $table->foreign('id_anggota')
                ->references('id_anggota')
                ->on('anggota')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('id_peminjaman')
                ->references('id_peminjaman')
                ->on('peminjaman')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('id_keterlambatan')
                ->references('id_keterlambatan')
                ->on('keterlambatan')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanksi_anggota');
    }
}
