<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengembalianTable extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->bigInteger('id_pengembalian')->primary();
            $table->bigInteger('id_peminjaman')->nullable();
            $table->bigInteger('id_petugas')->nullable();
            $table->timestamp('tanggal_pengembalian', 0)->nullable();
            $table->integer('total_hari_terlambat')->default(0);
            $table->string('status_pengembalian', 20)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->unique('id_peminjaman');
            $table->foreign('id_peminjaman')
                ->references('id_peminjaman')
                ->on('peminjaman')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('id_petugas')
                ->references('id_petugas')
                ->on('petugas')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
}
