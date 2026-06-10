<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPengembalianTable extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pengembalian', function (Blueprint $table) {
            $table->bigInteger('id_detail_pengembalian')->primary();
            $table->bigInteger('id_pengembalian')->nullable();
            $table->bigInteger('id_detail_peminjaman')->nullable();
            $table->integer('jumlah_dikembalikan')->nullable();
            $table->string('kondisi_buku', 30)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign('id_pengembalian')
                ->references('id_pengembalian')
                ->on('pengembalian')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('id_detail_peminjaman')
                ->references('id_detail_peminjaman')
                ->on('detail_peminjaman')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pengembalian');
    }
}
