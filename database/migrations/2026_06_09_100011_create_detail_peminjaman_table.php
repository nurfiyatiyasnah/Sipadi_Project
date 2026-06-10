<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPeminjamanTable extends Migration
{
    public function up(): void
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->bigInteger('id_detail_peminjaman')->primary();
            $table->bigInteger('id_peminjaman')->nullable();
            $table->bigInteger('id_buku')->nullable();
            $table->integer('jumlah')->nullable();
            $table->string('status_detail', 20)->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign('id_peminjaman')
                ->references('id_peminjaman')
                ->on('peminjaman')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('id_buku')
                ->references('id_buku')
                ->on('buku')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
}
