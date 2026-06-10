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
        Schema::create('aduan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_pelapor');
            $table->string('email_pelapor');
            $table->string('subjek');
            $table->text('isi_aduan');
            $table->enum('status_aduan', ['Baru', 'Diproses', 'Ditanggapi', 'Diarsipkan'])->default('Baru')->index();
            $table->text('tanggapan')->nullable();
            $table->foreignId('ditanggapi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('ditanggapi_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aduan');
    }
};
