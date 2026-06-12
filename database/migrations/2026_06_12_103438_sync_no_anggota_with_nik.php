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
        DB::table('anggota')
            ->select(['id_anggota', 'nik'])
            ->orderBy('id_anggota')
            ->each(function (object $anggota): void {
                DB::table('anggota')
                    ->where('id_anggota', $anggota->id_anggota)
                    ->update(['no_anggota' => $anggota->nik]);

                DB::table('e_kartu_anggota')
                    ->where('id_anggota', $anggota->id_anggota)
                    ->update(['no_anggota' => $anggota->nik]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nomor anggota lama tidak dapat direkonstruksi setelah disamakan dengan NIK.
    }
};
