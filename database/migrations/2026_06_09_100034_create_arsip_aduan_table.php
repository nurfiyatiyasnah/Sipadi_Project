<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArsipAduanTable extends Migration
{
    public function up(): void
    {
        Schema::create('arsip_aduan', function (Blueprint $table) {
            $table->bigInteger('id_arsip_aduan')->primary();
            $table->bigInteger('id_aduan')->nullable();
            $table->bigInteger('diarsipkan_oleh')->nullable();
            $table->text('alasan_diarsipkan')->nullable();
            $table->timestamp('diarsipkan_pada')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unique('id_aduan');
            $table->foreign('id_aduan')
                ->references('id_aduan')
                ->on('aduan')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('diarsipkan_oleh')
                ->references('id_petugas')
                ->on('petugas')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip_aduan');
    }
}
