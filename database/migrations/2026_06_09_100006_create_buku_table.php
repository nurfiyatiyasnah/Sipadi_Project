<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_buku')->cascadeOnDelete();
            $table->string('judul')->index();
            $table->string('isbn')->unique()->nullable();
            $table->string('penulis')->index();
            $table->string('penerbit');
            $table->year('tahun_terbit')->nullable();
            $table->string('edisi')->nullable();
            $table->string('bahasa')->nullable();
            $table->integer('jumlah_halaman')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('cover')->nullable();
            $table->string('lokasi_rak')->nullable();
            $table->integer('stok_total')->default(0);
            $table->integer('stok_tersedia')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
