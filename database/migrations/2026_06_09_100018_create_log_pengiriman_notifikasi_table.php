<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogPengirimanNotifikasiTable extends Migration
{
    public function up(): void
    {
        Schema::create('log_pengiriman_notifikasi', function (Blueprint $table) {

            $table->id('id_pengiriman_notifikasi');
            $table->unsignedBigInteger('id_notifikasi');
            $table->unsignedBigInteger('dikirim_oleh')->nullable();
            $table->string('via', 30)->nullable();
            $table->string('status_pengiriman', 20)->nullable();
            $table->text('pesan_error')->nullable();
            $table->timestamp('dikirim_pada')->nullable();
            $table->timestamps();

            $table->foreign('id_notifikasi')->references('id_notifikasi')->on('notifikasi');
            $table->foreign('dikirim_oleh')->references('id_petugas')->on('petugas');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_pengiriman_notifikasi');
    }
}
