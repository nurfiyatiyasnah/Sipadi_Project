<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanggapan_aduan', function (Blueprint $table) {
            $table->id('id_tanggapan');
            $table->foreignId('id_aduan')->constrained('aduan', 'id_aduan')->restrictOnDelete();
            $table->foreignId('id_petugas')->constrained('petugas', 'id_petugas')->restrictOnDelete();
            $table->text('isi_tanggapan');
            $table->string('status_setelah_respon', 20)->nullable();
            $table->timestamp('ditanggapi_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanggapan_aduan');
    }
};
