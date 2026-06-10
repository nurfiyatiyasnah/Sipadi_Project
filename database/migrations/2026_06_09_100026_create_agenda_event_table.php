<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendaEventTable extends Migration
{
    public function up(): void
    {
        Schema::create('agenda_event', function (Blueprint $table) {
            $table->bigInteger('id_event')->primary();
            $table->string('judul_event', 150)->nullable();
            $table->string('slug', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('lokasi', 150)->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->time('jam_mulai', 0)->nullable();
            $table->time('jam_selesai', 0)->nullable();
            $table->string('gambar', 255)->nullable();
            $table->string('status_event', 30)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign('created_by')
                ->references('id_petugas')
                ->on('petugas')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_event');
    }
}
