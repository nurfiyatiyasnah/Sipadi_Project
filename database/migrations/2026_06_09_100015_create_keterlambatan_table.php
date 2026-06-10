<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeterlambatanTable extends Migration
{
    public function up(): void
    {
        Schema::create('keterlambatan', function (Blueprint $table) {
            $table->bigInteger('id_keterlambatan')->primary();
            $table->bigInteger('id_peminjaman')->nullable();
            $table->bigInteger('id_anggota')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->date('tanggal_dihitung')->nullable();
            $table->integer('hari_terlambat')->nullable();
            $table->string('status_perhitungan', 20)->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->unique('id_peminjaman');
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
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keterlambatan');
    }
}
