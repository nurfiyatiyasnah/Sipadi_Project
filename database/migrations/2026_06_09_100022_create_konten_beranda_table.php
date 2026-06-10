<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKontenBerandaTable extends Migration
{
    public function up(): void
    {
        Schema::create('konten_beranda', function (Blueprint $table) {
            $table->bigInteger('id_konten_beranda')->primary();
            $table->string('judul', 150)->nullable();
            $table->text('isi')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->integer('urutan')->default(0);
            $table->string('status_konten', 20)->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konten_beranda');
    }
}
