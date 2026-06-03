<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user'); // BIGINT PK
            $table->foreignId('id_role')->constrained('roles', 'id_role')->onDelete('cascade'); // Foreign Key ke tabel roles (id_role)
            $table->string('nama', 100);
            $table->string('username', 100)->unique();
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('no_hp', 20)->nullable();
            $table->string('foto', 255)->nullable();
            $table->enum('status_akun', ['aktif', 'nonaktif'])->default('aktif');
            $table->dateTime('last_login')->nullable();
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};