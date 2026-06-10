<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {

            $table->id('id_role');
            $table->string('nama_role', 10)->unique();

            $table->text('deskripsi')->nullable();
            $table->timestamp('created_at ', 0)->nullable();
            $table->timestamp('updated_at ', 0)->nullable();
            $table->unique('nama_role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
}
