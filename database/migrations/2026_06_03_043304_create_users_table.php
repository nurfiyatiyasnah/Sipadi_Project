<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('id_user')->primary();
            $table->bigInteger('id_role')->nullable();
            $table->string('email', 50)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('status_akun', 30)->nullable();
            $table->timestamp('last_login_at', 0)->nullable();
            $table->timestampTz('created_at ', 0)->nullable();
            $table->timestamp('updated_at ', 0)->nullable();
            $table->unique('email');
            $table->foreign('id_role')
                ->references('id_role')
                ->on('roles')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
