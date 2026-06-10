<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aduan', function (Blueprint $table) {
            $table->id('id_aduan');
            $table->string('kode_aduan', 30)->unique();
            $table->foreignId('id_anggota')->constrained('anggota', 'id_anggota')->restrictOnDelete();
            $table->string('subjek', 150);
            $table->text('isi_aduan');
            $table->string('kategori_aduan', 30)->nullable();
            $table->string('lampiran', 255)->nullable();
            $table->string('status_aduan', 20)->nullable();
            $table->string('prioritas', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aduan');
    }
};
