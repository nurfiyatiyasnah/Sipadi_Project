<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogPengirimanNotifikasiTable extends Migration
{
    public function up(): void
    {
        Schema::create('log_pengiriman_notifikasi', function (Blueprint $table) {
            $table->bigInteger('id_pengiriman_notifikasi')->primary();
            $table->bigInteger('id_notifikasi')->nullable();
            $table->bigInteger('dikirim_oleh')->nullable();
            $table->string('via', 30)->nullable();
            $table->string('status_pengiriman', 20)->nullable();
            $table->text('pesan_error')->nullable();
            $table->timestamp('dikirim_pada', 0)->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign('id_notifikasi')
                ->references('id_notifikasi')
                ->on('notifikasi')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('dikirim_oleh')
                ->references('id_petugas')
                ->on('petugas')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_pengiriman_notifikasi');
    }
}
