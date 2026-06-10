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
        Schema::create('keterlambatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->cascadeOnDelete();
            $table->integer('jumlah_hari');
            $table->decimal('denda_per_hari', 10, 2)->default(0);
            $table->decimal('total_denda', 12, 2)->default(0);
            $table->enum('status_denda', ['Belum Bayar', 'Lunas', 'Dibebaskan'])->default('Belum Bayar')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keterlambatan');
    }
};
