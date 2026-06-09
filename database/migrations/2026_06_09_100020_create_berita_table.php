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
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_berita')->cascadeOnDelete();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('isi');
            $table->string('gambar')->nullable();
            $table->enum('status', ['Draft', 'Terbit', 'Diarsipkan'])->default('Draft')->index();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('tanggal_publish')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
