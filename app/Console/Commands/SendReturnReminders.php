<?php

namespace App\Console\Commands;

use App\Jobs\SendFonnteReminderJob;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use Carbon\CarbonInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendReturnReminders extends Command
{
    private const UPCOMING_REMINDER_DAYS = 2;

    private const UPCOMING_REMINDER_TYPE = 'reminder_sebelum_jatuh_tempo';

    private const OVERDUE_REMINDER_TYPE = 'reminder_keterlambatan';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:send-return-reminders
        {--before-days=2 : Jumlah hari sebelum jatuh tempo untuk reminder pengembalian.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp return reminders before and after book loan due dates.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $upcomingCount = $this->sendUpcomingReminders();
        $overdueCount = $this->sendOverdueReminders();

        $this->info("Reminder sebelum jatuh tempo dibuat: {$upcomingCount}");
        $this->info("Reminder keterlambatan dibuat: {$overdueCount}");

        return self::SUCCESS;
    }

    private function sendUpcomingReminders(): int
    {
        $createdCount = 0;
        $dueDate = today()->addDays($this->upcomingReminderDays())->toDateString();

        Peminjaman::with(['anggota', 'detailPeminjaman.buku'])
            ->where('status_peminjaman', 'aktif')
            ->whereDate('tanggal_jatuh_tempo', $dueDate)
            ->whereHas('anggota', function ($query): void {
                $query->whereNotNull('no_telepon')
                    ->where('no_telepon', '<>', '');
            })
            ->chunkById(100, function ($peminjamans) use (&$createdCount): void {
                foreach ($peminjamans as $peminjaman) {
                    $notifikasi = $this->createReminderNotification($peminjaman, self::UPCOMING_REMINDER_TYPE);

                    if ($notifikasi) {
                        SendFonnteReminderJob::dispatch($notifikasi->id_notifikasi);
                        $createdCount++;
                    }
                }
            }, 'id_peminjaman');

        return $createdCount;
    }

    private function sendOverdueReminders(): int
    {
        $createdCount = 0;

        Peminjaman::with(['anggota', 'detailPeminjaman.buku'])
            ->whereIn('status_peminjaman', ['aktif', 'terlambat'])
            ->whereDate('tanggal_jatuh_tempo', '<', today()->toDateString())
            ->whereHas('anggota', function ($query): void {
                $query->whereNotNull('no_telepon')
                    ->where('no_telepon', '<>', '');
            })
            ->chunkById(100, function ($peminjamans) use (&$createdCount): void {
                foreach ($peminjamans as $peminjaman) {
                    $notifikasi = $this->createReminderNotification($peminjaman, self::OVERDUE_REMINDER_TYPE);

                    if ($notifikasi) {
                        SendFonnteReminderJob::dispatch($notifikasi->id_notifikasi);
                        $createdCount++;
                    }
                }
            }, 'id_peminjaman');

        return $createdCount;
    }

    private function createReminderNotification(Peminjaman $peminjaman, string $type): ?Notifikasi
    {
        if ($this->alreadySentToday($peminjaman, $type)) {
            return null;
        }

        return DB::transaction(function () use ($peminjaman, $type): ?Notifikasi {
            $peminjaman->refresh();

            if ($this->alreadySentToday($peminjaman, $type)) {
                return null;
            }

            if ($type === self::OVERDUE_REMINDER_TYPE && $peminjaman->status_peminjaman === 'aktif') {
                $peminjaman->update([
                    'status_peminjaman' => 'terlambat',
                ]);
            }

            return Notifikasi::create([
                'id_user' => $peminjaman->anggota->id_user,
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'judul' => $this->notificationTitle($type),
                'isi' => $this->notificationMessage($peminjaman, $type),
                'jenis_notifikasi' => $type,
                'status_notifikasi' => 'menunggu',
                'status_baca' => 'belum_dibaca',
                'dikirim_pada' => now(),
            ]);
        });
    }

    private function alreadySentToday(Peminjaman $peminjaman, string $type): bool
    {
        return Notifikasi::where('id_peminjaman', $peminjaman->id_peminjaman)
            ->where('jenis_notifikasi', $type)
            ->whereDate('dikirim_pada', today())
            ->exists();
    }

    private function notificationTitle(string $type): string
    {
        return match ($type) {
            self::UPCOMING_REMINDER_TYPE => 'Pengingat Pengembalian Buku',
            self::OVERDUE_REMINDER_TYPE => 'Peringatan Keterlambatan Pengembalian',
            default => 'Pengingat Peminjaman Buku',
        };
    }

    private function notificationMessage(Peminjaman $peminjaman, string $type): string
    {
        if ($type === self::UPCOMING_REMINDER_TYPE) {
            return $this->upcomingReminderMessage($peminjaman);
        }

        return $this->overdueReminderMessage($peminjaman);
    }

    private function upcomingReminderMessage(Peminjaman $peminjaman): string
    {
        return "Yth. {$this->memberName($peminjaman)},\n\n"
            ."Kami dari Perpustakaan SIPADI mengingatkan bahwa masa peminjaman buku Anda akan segera berakhir.\n\n"
            ."Kode Peminjaman: {$peminjaman->kode_peminjaman}\n"
            ."Judul Buku: {$this->bookTitle($peminjaman)}\n"
            ."Tanggal Jatuh Tempo: {$this->formattedDueDate($peminjaman)}\n"
            ."Sisa Waktu: {$this->upcomingReminderDays()} hari\n\n"
            ."Mohon mengembalikan buku tepat waktu agar tidak terkena sanksi keterlambatan.\n\n"
            ."Terima kasih.\n"
            .'Perpustakaan SIPADI';
    }

    private function overdueReminderMessage(Peminjaman $peminjaman): string
    {
        return "Yth. {$this->memberName($peminjaman)},\n\n"
            ."Kami dari Perpustakaan SIPADI mengingatkan bahwa peminjaman buku Anda telah melewati batas pengembalian.\n\n"
            ."Kode Peminjaman: {$peminjaman->kode_peminjaman}\n"
            ."Judul Buku: {$this->bookTitle($peminjaman)}\n"
            ."Tanggal Jatuh Tempo: {$this->formattedDueDate($peminjaman)}\n"
            ."Keterlambatan: {$this->lateDays($peminjaman)} hari\n\n"
            ."Mohon segera mengembalikan buku ke perpustakaan. Abaikan pesan ini jika Anda sudah mengembalikan buku.\n\n"
            ."Terima kasih.\n"
            .'Perpustakaan SIPADI';
    }

    private function memberName(Peminjaman $peminjaman): string
    {
        return $peminjaman->anggota?->nama_lengkap ?? 'Anggota';
    }

    private function bookTitle(Peminjaman $peminjaman): string
    {
        return $peminjaman->detailPeminjaman->first()?->buku?->judul ?? 'Buku';
    }

    private function formattedDueDate(Peminjaman $peminjaman): string
    {
        $dueDate = $peminjaman->tanggal_jatuh_tempo;

        if (! $dueDate instanceof CarbonInterface) {
            return '-';
        }

        return $dueDate->locale('id')->translatedFormat('d F Y');
    }

    private function lateDays(Peminjaman $peminjaman): int
    {
        $dueDate = $peminjaman->tanggal_jatuh_tempo;

        if (! $dueDate instanceof CarbonInterface) {
            return 0;
        }

        return max(1, (int) $dueDate->startOfDay()->diffInDays(today(), false));
    }

    private function upcomingReminderDays(): int
    {
        $days = $this->option('before-days');

        if (! is_numeric($days)) {
            return self::UPCOMING_REMINDER_DAYS;
        }

        return max(0, (int) $days);
    }
}
