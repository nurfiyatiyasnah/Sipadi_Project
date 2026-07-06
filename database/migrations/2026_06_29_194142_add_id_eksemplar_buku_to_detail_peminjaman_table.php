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
        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->unsignedBigInteger('id_eksemplar_buku')->nullable()->after('id_buku');
            $table->foreign('id_eksemplar_buku')
                ->references('id_eksemplar_buku')
                ->on('eksemplar_buku')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->dropForeign(['id_eksemplar_buku']);
            $table->dropColumn('id_eksemplar_buku');
        });
    }
};
