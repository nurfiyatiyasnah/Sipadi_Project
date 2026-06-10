<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKunjunganTable extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->bigInteger('id_kunjugan')->primary();
            $table->string('kode_kunjungan', 30)->nullable();
            $table->bigInteger('id_anggota')->nullable();
            $table->string('nama_pengunjung', 100)->nullable();
            $table->string('jenis_pengunjung', 30)->nullable();
            $table->string('no_identitas', 50)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('instansi', 150)->nullable();
            $table->integer('jumlah_kunjungan')->default(1);
            $table->string('tujuan_kunjungan', 150)->nullable();
            $table->date('tanggal_kunjungan')->nullable();
            $table->time('jam_masuk', 0)->nullable();
            $table->time('jam_keluar', 0)->nullable();
            $table->string('status_kunjungan', 20)->nullable();
            $table->bigInteger('dicatat_oleh')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->unique('kode_kunjungan');
            $table->foreign('id_anggota')
                ->references('id_anggota')
                ->on('anggota')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('dicatat_oleh')
                ->references('id_petugas')
                ->on('petugas')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
}
