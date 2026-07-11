<?php

namespace App\Jobs;

use App\Models\LogPengirimanNotifikasi;
use App\Models\Notifikasi;
use App\Services\FonnteClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class SendFonnteReminderJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 30;

    /**
     * @var array<int, int>
     */
    public array $backoff = [60, 300, 900];

    public function __construct(public int $notifikasiId) {}

    /**
     * Execute the job.
     */
    public function handle(FonnteClient $fonnteClient): void
    {
        $notifikasi = Notifikasi::with(['peminjaman.anggota', 'peminjaman.detailPeminjaman.buku'])
            ->findOrFail($this->notifikasiId);

        try {
            $phoneNumber = trim((string) $notifikasi->peminjaman?->anggota?->no_telepon);

            if ($phoneNumber === '') {
                $this->recordFailure($notifikasi, 'Nomor WhatsApp anggota belum tersedia.');

                return;
            }

            $fonnteClient->sendMessage($phoneNumber, (string) $notifikasi->isi);

            LogPengirimanNotifikasi::create([
                'id_notifikasi' => $notifikasi->id_notifikasi,
                'dikirim_oleh' => null,
                'via' => 'whatsapp_fonnte',
                'status_pengiriman' => 'berhasil',
                'pesan_error' => null,
                'dikirim_pada' => now(),
            ]);

            $notifikasi->update([
                'status_notifikasi' => 'terkirim',
                'dikirim_pada' => now(),
            ]);
        } catch (Throwable $exception) {
            $this->recordFailure($notifikasi, $exception->getMessage());

            throw $exception;
        }
    }

    public function failed(?Throwable $exception): void
    {
        $notifikasi = Notifikasi::find($this->notifikasiId);

        if (! $notifikasi) {
            return;
        }

        $hasFailedLogToday = LogPengirimanNotifikasi::where('id_notifikasi', $notifikasi->id_notifikasi)
            ->where('via', 'whatsapp_fonnte')
            ->where('status_pengiriman', 'gagal')
            ->whereDate('created_at', today())
            ->exists();

        if (! $hasFailedLogToday) {
            $this->recordFailure($notifikasi, $exception?->getMessage() ?? 'Pengiriman reminder Fonnte gagal.');
        }
    }

    private function recordFailure(Notifikasi $notifikasi, string $message): void
    {
        LogPengirimanNotifikasi::create([
            'id_notifikasi' => $notifikasi->id_notifikasi,
            'dikirim_oleh' => null,
            'via' => 'whatsapp_fonnte',
            'status_pengiriman' => 'gagal',
            'pesan_error' => $message,
            'dikirim_pada' => now(),
        ]);

        $notifikasi->update([
            'status_notifikasi' => 'gagal',
            'dikirim_pada' => now(),
        ]);
    }
}
