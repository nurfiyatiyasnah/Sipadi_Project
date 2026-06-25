<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->string('slug', 150)->nullable()->unique()->after('judul');
            $table->string('prioritas', 20)->default('Normal')->after('status_pengumuman');
            $table->string('target_pengguna', 50)->default('Semua')->after('prioritas');
            $table->integer('total_views')->default(0)->after('target_pengguna');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->dropColumn(['slug', 'prioritas', 'target_pengguna', 'total_views']);
        });
    }
};
