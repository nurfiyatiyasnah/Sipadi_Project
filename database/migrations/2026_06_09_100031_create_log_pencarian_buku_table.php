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
        Schema::create('log_pencarian_buku', function (Blueprint $table) {
            $table->id('id_log_pencarian');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->string('keyword', 200)->nullable();
            $table->integer('jumlah_hasil')->default(0);
            $table->string('ip_address', 50)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_pencarian_buku');
    }
};
