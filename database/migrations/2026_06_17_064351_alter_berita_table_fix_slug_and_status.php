<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            $table->string('slug', 225)->change();
            $table->string('status_berita', 30)->default('draft')->change();
            $table->index('status_berita');
        });
    }

    public function down(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            $table->dropIndex(['status_berita']);
            $table->char('slug', 225)->change();
            $table->string('status_berita', 30)->nullable()->change();
        });
    }
};
