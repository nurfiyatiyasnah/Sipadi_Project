<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\AgendaEvent;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = $this->dashboardStats();
        $aktivitas_terkini = $this->latestActivities();
        $aksi_cepat = $this->quickActions();
        $status_layanan = $this->serviceStatuses($stats);
        $prioritas_hari_ini = $this->todayPriorities($stats);

        $peminjaman_terbaru = $this->getPeminjamanTerbaru();
        $agenda_terdekat = $this->getAgendaTerdekat();

        return view(
            'dashboard.index',
            compact(
                'stats',
                'aktivitas_terkini',
                'aksi_cepat',
                'status_layanan',
                'prioritas_hari_ini',
                'peminjaman_terbaru',
                'agenda_terdekat'
            )
        );
    }

    /**
     * @return array{total_anggota: int, koleksi_buku: int, peminjaman_aktif: int, pengajuan_peminjaman: int, buku_terlambat: int, aduan_baru: int}
     */
    private function dashboardStats(): array
    {
        return [
            'total_anggota' => Anggota::count(),
            'koleksi_buku' => Buku::count(),
            'peminjaman_aktif' => Peminjaman::where('status_peminjaman', 'aktif')->count(),
            'aduan_baru' => Aduan::where('status_aduan', 'baru')->count(),
        ];
    }

    /**
     * Get latest borrowing activities.
     *
     * @return array<int, array{nama_anggota: string, judul_buku: string, tanggal_pinjam: string, status: string}>
     */
    private function getPeminjamanTerbaru(): array
    {
        $records = Peminjaman::with(['anggota', 'detailPeminjaman.buku'])
            ->latest('id_peminjaman')
            ->limit(4)
            ->get();

        return $records->map(function ($peminjaman) {
            $tanggal = $peminjaman->tanggal_diambil
                ? $peminjaman->tanggal_diambil->locale('id')->translatedFormat('d M Y')
                : ($peminjaman->tanggal_pengajuan
                    ? $peminjaman->tanggal_pengajuan->locale('id')->translatedFormat('d M Y')
                    : $peminjaman->created_at->locale('id')->translatedFormat('d M Y'));

            return [
                'nama_anggota' => $peminjaman->anggota?->nama_lengkap ?? 'Anggota',
                'judul_buku' => $peminjaman->detailPeminjaman->first()?->buku?->judul ?? 'Buku',
                'tanggal_pinjam' => $tanggal,
                'status' => strtolower($peminjaman->status_peminjaman ?? 'pending'),
            ];
        })->all();
    }

    /**
     * Get nearest agenda events.
     *
     * @return array<int, array{bulan: string, tanggal: string, judul: string, waktu: string, lokasi: string}>
     */
    private function getAgendaTerdekat(): array
    {
        $records = AgendaEvent::query()
            ->where('tanggal_mulai', '>=', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->limit(3)
            ->get();

        if ($records->isEmpty()) {
            $records = AgendaEvent::query()
                ->orderBy('tanggal_mulai', 'desc')
                ->limit(3)
                ->get();
        }

        if ($records->isEmpty()) {
            return [
                [
                    'bulan' => 'Okt',
                    'tanggal' => '20',
                    'judul' => 'Webinar Literasi Digital',
                    'waktu' => '09:00 - 11:00 WIB',
                    'lokasi' => 'Zoom Meeting',
                ],
                [
                    'bulan' => 'Okt',
                    'tanggal' => '25',
                    'judul' => 'Rapat Evaluasi Bulanan',
                    'waktu' => '13:00 - 15:00 WIB',
                    'lokasi' => 'Ruang Rapat Utama',
                ],
                [
                    'bulan' => 'Nov',
                    'tanggal' => '02',
                    'judul' => 'Penerimaan Buku Baru',
                    'waktu' => '08:00 WIB',
                    'lokasi' => 'Gudang Perpustakaan',
                ],
            ];
        }

        return $records->map(function ($event) {
            $start = $event->tanggal_mulai;
            $bulan = $start ? $start->locale('id')->translatedFormat('M') : 'Okt';
            $tanggal = $start ? $start->format('d') : '20';

            $waktu = '';
            if ($event->jam_mulai) {
                $waktu .= substr($event->jam_mulai, 0, 5);
                if ($event->jam_selesai) {
                    $waktu .= ' - '.substr($event->jam_selesai, 0, 5);
                }
                $waktu .= ' WIB';
            } else {
                $waktu = '08:00 WIB';
            }

            return [
                'bulan' => $bulan,
                'tanggal' => $tanggal,
                'judul' => $event->judul_event,
                'waktu' => $waktu,
                'lokasi' => $event->lokasi ?? 'Perpustakaan',
            ];
        })->all();
    }

    /**
     * @return array<int, array{icon: string, judul: string, deskripsi: string, status: ?string, waktu: string}>
     */
    private function latestActivities(): array
    {
        $activities = [];

        $pengembalian = Pengembalian::query()
            ->with(['peminjaman.anggota', 'peminjaman.detailPeminjaman.buku'])
            ->latest()
            ->first();

        if ($pengembalian) {
            $judulBuku = $pengembalian->peminjaman?->detailPeminjaman->first()?->buku?->judul ?? 'koleksi perpustakaan';
            $anggota = $pengembalian->peminjaman?->anggota;

            $activities[] = [
                'icon' => 'fa-regular fa-clipboard',
                'judul' => 'Pengembalian Buku "'.$judulBuku.'"',
                'deskripsi' => 'Anggota: '.($anggota?->nama_lengkap ?? 'Tidak diketahui').' (ID: '.($anggota?->no_anggota ?? '-').')',
                'status' => $pengembalian->status_pengembalian,
                'waktu' => $pengembalian->created_at?->diffForHumans() ?? 'Baru saja',
            ];
        }

        $anggotaBaru = Anggota::query()->latest()->first();

        if ($anggotaBaru) {
            $activities[] = [
                'icon' => 'fa-solid fa-user-plus',
                'judul' => 'Registrasi Anggota Baru',
                'deskripsi' => $anggotaBaru->nama_lengkap.' telah bergabung sebagai anggota perpustakaan.',
                'status' => $anggotaBaru->status_anggota,
                'waktu' => $anggotaBaru->created_at?->diffForHumans() ?? 'Baru saja',
            ];
        }

        $bukuBaru = Buku::query()->latest()->first();

        if ($bukuBaru) {
            $activities[] = [
                'icon' => 'fa-solid fa-box-archive',
                'judul' => 'Pembaruan Stok Koleksi',
                'deskripsi' => 'Koleksi "'.$bukuBaru->judul.'" tersedia di katalog perpustakaan.',
                'status' => $bukuBaru->status_katalog,
                'waktu' => $bukuBaru->created_at?->diffForHumans() ?? 'Baru saja',
            ];
        }

        return array_slice($activities ?: $this->emptyActivities(), 0, 3);
    }

    /**
     * @return array<int, array{icon: string, judul: string, deskripsi: string, status: ?string, waktu: string}>
     */
    private function emptyActivities(): array
    {
        return [
            [
                'icon' => 'fa-regular fa-clipboard',
                'judul' => 'Belum Ada Pengembalian',
                'deskripsi' => 'Aktivitas pengembalian buku akan muncul setelah ada data transaksi.',
                'status' => null,
                'waktu' => '-',
            ],
            [
                'icon' => 'fa-solid fa-user-plus',
                'judul' => 'Belum Ada Anggota Baru',
                'deskripsi' => 'Registrasi anggota terbaru akan tampil otomatis dari database.',
                'status' => null,
                'waktu' => '-',
            ],
            [
                'icon' => 'fa-solid fa-box-archive',
                'judul' => 'Belum Ada Pembaruan Koleksi',
                'deskripsi' => 'Koleksi buku terbaru akan tampil otomatis dari database.',
                'status' => null,
                'waktu' => '-',
            ],
        ];

        $aksi_cepat = [
            ['label' => 'Tambah Buku'],
            ['label' => 'Tambah Anggota'],
            ['label' => 'Kelola Peminjaman'],
            ['label' => 'Kelola Berita'],
            ['label' => 'Lihat Laporan'],
        ];

        return view(
            'dashboard.index',
            compact(
                'stats',
                'aktivitas_terkini',
                'aksi_cepat'
            )
        );
    }

    public function koleksi(Request $request): View
    {
        $query = Buku::query()
            ->with('kategori')
            ->withCount('eksemplar')
            ->withCount([
                'eksemplar as eksemplar_tersedia_count' => fn ($query) => $query
                    ->whereIn('status_eksemplar', ['tersedia', 'Tersedia']),
            ]);

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status_katalog', $request->status);
        }

        $totalBuku = Buku::count();
        $tersedia = Buku::where('status_katalog', 'Tersedia')->count();

        $stats = [
            'judul' => Buku::count(),
            'eksemplar' => EksemplarBuku::count(),
            'dipinjam' => EksemplarBuku::whereIn('status_eksemplar', ['dipinjam', 'Dipinjam'])->count(),
            'tersedia' => EksemplarBuku::whereIn('status_eksemplar', ['tersedia', 'Tersedia'])->count(),
        ];
        $stats['persen'] = round($stats['tersedia'] / max($stats['eksemplar'], 1) * 100, 1);

        $categories = KategoriBuku::query()
            ->orderBy('nama_kategori')
            ->get(['id_kategori', 'nama_kategori']);
        $books = $query->latest('id_buku')->paginate(10)->withQueryString();


        return view('admin.books.koleksi', compact('stats', 'categories', 'books'));
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function (): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Kode', 'Judul', 'ISBN', 'Penulis', 'Kategori', 'Total Eksemplar', 'Tersedia', 'Status Katalog']);

            Buku::query()
                ->with('kategori')
                ->withCount('eksemplar')
                ->withCount([
                    'eksemplar as eksemplar_tersedia_count' => fn ($query) => $query
                        ->whereIn('status_eksemplar', ['tersedia', 'Tersedia']),
                ])
                ->orderBy('id_buku')
                ->each(function (Buku $buku) use ($output): void {
                    fputcsv($output, [
                        $buku->kode_buku,
                        $buku->judul,
                        $buku->isbn,
                        $buku->penulis,
                        $buku->kategori?->nama_kategori,
                        $buku->eksemplar_count,
                        $buku->eksemplar_tersedia_count,
                        $buku->status_katalog,
                    ]);
                });

            fclose($output);
        }, 'koleksi_buku.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
