<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('eksemplar_buku')
            ->whereNotNull('status_eksemplar')
            ->update([
                'status_eksemplar' => DB::raw('LOWER(status_eksemplar)'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Normalisasi status ke lowercase tidak dapat dikembalikan ke casing lama secara akurat.
    }
};
