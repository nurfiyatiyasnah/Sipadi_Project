<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKunjunganTable extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungan', function (Blueprint $table) {

            $table->id('id_kunjugan');
            $table->string('kode_kunjungan', 30)->unique();
            $table->unsignedBigInteger('id_anggota')->nullable();

            $table->string('nama_pengunjung', 100)->nullable();
            $table->string('jenis_pengunjung', 30)->nullable();
            $table->string('no_identitas', 50)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('instansi', 150)->nullable();
            $table->integer('jumlah_kunjungan')->default(1);
            $table->string('tujuan_kunjungan', 150)->nullable();
            $table->date('tanggal_kunjungan')->nullable();

            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->string('status_kunjungan', 20)->nullable();
            $table->unsignedBigInteger('dicatat_oleh')->nullable();
            $table->timestamps();

            $table->foreign('id_anggota')->references('id_anggota')->on('anggota');
            $table->foreign('dicatat_oleh')->references('id_petugas')->on('petugas');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
}
