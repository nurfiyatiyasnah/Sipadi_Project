<?php

namespace Tests\Feature;

use App\Jobs\SendFonnteReminderJob;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Services\FonnteClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use RuntimeException;
use Tests\TestCase;

class ReturnReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_reminder_sebelum_jatuh_tempo_dibuat_untuk_peminjaman_aktif_yang_jatuh_tempo_dua_hari_lagi(): void
    {
        Queue::fake();

        $peminjaman = $this->createLoan([
            'status_peminjaman' => 'aktif',
            'tanggal_jatuh_tempo' => today()->addDays(2),
        ]);

        $this->artisan('books:send-return-reminders')
            ->assertExitCode(0);

        $this->assertDatabaseHas('notifikasi', [
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'jenis_notifikasi' => 'reminder_sebelum_jatuh_tempo',
            'judul' => 'Pengingat Pengembalian Buku',
        ]);

        Queue::assertPushed(SendFonnteReminderJob::class, function (SendFonnteReminderJob $job) use ($peminjaman): bool {
            $notifikasi = Notifikasi::find($job->notifikasiId);

            return $notifikasi?->id_peminjaman === $peminjaman->id_peminjaman
                && $notifikasi->jenis_notifikasi === 'reminder_sebelum_jatuh_tempo';
        });
    }

    public function test_reminder_sebelum_jatuh_tempo_tidak_dibuat_untuk_tanggal_lain(): void
    {
        Queue::fake();

        $peminjaman = $this->createLoan([
            'status_peminjaman' => 'aktif',
            'tanggal_jatuh_tempo' => today()->addDays(3),
        ]);

        $this->artisan('books:send-return-reminders')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('notifikasi', [
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'jenis_notifikasi' => 'reminder_sebelum_jatuh_tempo',
        ]);

        Queue::assertNotPushed(SendFonnteReminderJob::class);
    }

    public function test_reminder_sebelum_jatuh_tempo_bisa_diuji_dengan_opsi_before_days(): void
    {
        Queue::fake();

        $peminjaman = $this->createLoan([
            'status_peminjaman' => 'aktif',
            'tanggal_jatuh_tempo' => today()->addDays(14),
        ]);

        $this->artisan('books:send-return-reminders', [
            '--before-days' => 14,
        ])->assertExitCode(0);

        $notifikasi = Notifikasi::where('id_peminjaman', $peminjaman->id_peminjaman)
            ->where('jenis_notifikasi', 'reminder_sebelum_jatuh_tempo')
            ->first();

        $this->assertNotNull($notifikasi);
        $this->assertStringContainsString('Sisa Waktu: 14 hari', (string) $notifikasi->isi);

        Queue::assertPushed(SendFonnteReminderJob::class);
    }

    public function test_reminder_keterlambatan_dibuat_dan_status_aktif_diubah_menjadi_terlambat(): void
    {
        Queue::fake();

        $peminjaman = $this->createLoan([
            'status_peminjaman' => 'aktif',
            'tanggal_jatuh_tempo' => today()->subDay(),
        ]);

        $this->artisan('books:send-return-reminders')
            ->assertExitCode(0);

        $peminjaman->refresh();

        $this->assertEquals('terlambat', $peminjaman->status_peminjaman);
        $this->assertDatabaseHas('notifikasi', [
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'jenis_notifikasi' => 'reminder_keterlambatan',
            'judul' => 'Peringatan Keterlambatan Pengembalian',
        ]);

        Queue::assertPushed(SendFonnteReminderJob::class);
    }

    public function test_reminder_keterlambatan_dibuat_untuk_peminjaman_yang_sudah_berstatus_terlambat(): void
    {
        Queue::fake();

        $peminjaman = $this->createLoan([
            'status_peminjaman' => 'terlambat',
            'tanggal_jatuh_tempo' => today()->subDays(2),
        ]);

        $this->artisan('books:send-return-reminders')
            ->assertExitCode(0);

        $this->assertDatabaseHas('notifikasi', [
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'jenis_notifikasi' => 'reminder_keterlambatan',
        ]);

        Queue::assertPushed(SendFonnteReminderJob::class);
    }

    public function test_reminder_tidak_dibuat_dobel_untuk_jenis_yang_sama_pada_hari_yang_sama(): void
    {
        Queue::fake();

        $peminjaman = $this->createLoan([
            'status_peminjaman' => 'aktif',
            'tanggal_jatuh_tempo' => today()->addDays(2),
        ]);

        Notifikasi::create([
            'id_user' => $peminjaman->anggota->id_user,
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'judul' => 'Pengingat Pengembalian Buku',
            'isi' => 'Reminder sudah dikirim.',
            'jenis_notifikasi' => 'reminder_sebelum_jatuh_tempo',
            'status_notifikasi' => 'terkirim',
            'status_baca' => 'belum_dibaca',
            'dikirim_pada' => now(),
        ]);

        $this->artisan('books:send-return-reminders')
            ->assertExitCode(0);

        $this->assertEquals(1, Notifikasi::where('id_peminjaman', $peminjaman->id_peminjaman)
            ->where('jenis_notifikasi', 'reminder_sebelum_jatuh_tempo')
            ->count());

        Queue::assertNotPushed(SendFonnteReminderJob::class);
    }

    public function test_job_mengirim_request_ke_fonnte_dan_mencatat_log_berhasil(): void
    {
        config([
            'services.fonnte.base_url' => 'https://api.fonnte.com',
            'services.fonnte.token' => 'test-token',
            'services.fonnte.country_code' => '62',
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'https://api.fonnte.com/send' => Http::response([
                'detail' => 'success! message in queue',
                'process' => 'pending',
                'status' => true,
                'target' => ['6281234567890'],
            ]),
        ]);

        $peminjaman = $this->createLoan();
        $notifikasi = $this->createReminderNotification($peminjaman, 'reminder_sebelum_jatuh_tempo', 'Isi reminder testing.');

        (new SendFonnteReminderJob($notifikasi->id_notifikasi))->handle(app(FonnteClient::class));

        Http::assertSent(function (Request $request): bool {
            return $request->url() === 'https://api.fonnte.com/send'
                && $request->hasHeader('Authorization', 'test-token')
                && $request['target'] === '081234567890'
                && $request['message'] === 'Isi reminder testing.'
                && $request['countryCode'] === '62';
        });

        $this->assertDatabaseHas('log_pengiriman_notifikasi', [
            'id_notifikasi' => $notifikasi->id_notifikasi,
            'via' => 'whatsapp_fonnte',
            'status_pengiriman' => 'berhasil',
        ]);

        $this->assertDatabaseHas('notifikasi', [
            'id_notifikasi' => $notifikasi->id_notifikasi,
            'status_notifikasi' => 'terkirim',
        ]);
    }

    public function test_job_mencatat_log_gagal_saat_fonnte_mengembalikan_error(): void
    {
        config([
            'services.fonnte.base_url' => 'https://api.fonnte.com',
            'services.fonnte.token' => 'test-token',
            'services.fonnte.country_code' => '62',
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'https://api.fonnte.com/send' => Http::response([
                'reason' => 'target invalid',
                'status' => false,
            ]),
        ]);

        $peminjaman = $this->createLoan();
        $notifikasi = $this->createReminderNotification($peminjaman, 'reminder_keterlambatan', 'Isi reminder gagal.');

        try {
            (new SendFonnteReminderJob($notifikasi->id_notifikasi))->handle(app(FonnteClient::class));
            $this->fail('Job seharusnya melempar exception ketika Fonnte gagal.');
        } catch (RuntimeException $exception) {
            $this->assertSame('target invalid', $exception->getMessage());
        }

        $this->assertDatabaseHas('log_pengiriman_notifikasi', [
            'id_notifikasi' => $notifikasi->id_notifikasi,
            'via' => 'whatsapp_fonnte',
            'status_pengiriman' => 'gagal',
            'pesan_error' => 'target invalid',
        ]);

        $this->assertDatabaseHas('notifikasi', [
            'id_notifikasi' => $notifikasi->id_notifikasi,
            'status_notifikasi' => 'gagal',
        ]);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createLoan(array $attributes = []): Peminjaman
    {
        $anggota = Anggota::factory()->create([
            'nama_lengkap' => 'Budi Santoso',
            'no_telepon' => '081234567890',
        ]);

        $buku = Buku::factory()->create([
            'judul' => 'Laskar Pelangi',
        ]);

        $peminjaman = Peminjaman::create(array_merge([
            'kode_peminjaman' => uniqid('PJM-'),
            'id_anggota' => $anggota->id_anggota,
            'status_peminjaman' => 'aktif',
            'tanggal_pengajuan' => now()->subDays(7),
            'tanggal_diambil' => now()->subDays(5),
            'tanggal_jatuh_tempo' => today()->addDays(2),
        ], $attributes));

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1,
            'status_detail' => 'dipinjam',
        ]);

        return $peminjaman->load(['anggota', 'detailPeminjaman.buku']);
    }

    private function createReminderNotification(Peminjaman $peminjaman, string $type, string $message): Notifikasi
    {
        return Notifikasi::create([
            'id_user' => $peminjaman->anggota->id_user,
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'judul' => 'Reminder Testing',
            'isi' => $message,
            'jenis_notifikasi' => $type,
            'status_notifikasi' => 'menunggu',
            'status_baca' => 'belum_dibaca',
            'dikirim_pada' => now(),
        ]);
    }
}
