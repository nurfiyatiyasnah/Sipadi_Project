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
        Schema::table('agenda_event', function (Blueprint $table) {
            $table->string('kategori', 255)->nullable()->after('status_event');
            $table->boolean('tampilkan_beranda')->default(false)->after('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agenda_event', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'tampilkan_beranda']);
        });
    }
};
