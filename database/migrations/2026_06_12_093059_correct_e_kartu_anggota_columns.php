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
        Schema::table('e_kartu_anggota', function (Blueprint $table) {
            $table->string('no_anggota', 30)->change();
            $table->string('barcode', 100)->nullable()->unique()->change();
            $table->renameColumn('update_at', 'updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('e_kartu_anggota', function (Blueprint $table) {
            $table->dropUnique(['barcode']);
            $table->renameColumn('updated_at', 'update_at');
            $table->unsignedBigInteger('no_anggota')->change();
            $table->unsignedBigInteger('barcode')->nullable()->change();
        });
    }
};
