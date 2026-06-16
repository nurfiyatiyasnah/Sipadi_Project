<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
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

        return view(
            'dashboard.index',
            compact(
                'stats',
                'aktivitas_terkini',
                'aksi_cepat',
                'status_layanan',
                'prioritas_hari_ini'
            )
        );
    }

    /**
     * @return array{total_anggota: int, koleksi_buku: int, peminjaman_aktif: int, aduan_baru: int}
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
    }

    /**
     * @return array<int, array{label: string, icon: string}>
     */
    private function quickActions(): array
    {
        return [
            ['label' => 'Tambah Buku', 'icon' => 'fa-solid fa-circle-plus'],
            ['label' => 'Peminjaman', 'icon' => 'fa-solid fa-paper-plane'],
            ['label' => 'Atur Jadwal', 'icon' => 'fa-regular fa-calendar'],
            ['label' => 'Tanggapi Aduan', 'icon' => 'fa-regular fa-message'],
        ];
    }

    /**
     * @param  array{total_anggota: int, koleksi_buku: int, peminjaman_aktif: int, aduan_baru: int}  $stats
     * @return array<int, array{label: string, value: string, tone: string, icon: string}>
     */
    private function serviceStatuses(array $stats): array
    {
        return [
            [
                'label' => 'Pendaftaran Anggota',
                'value' => 'Aktif',
                'tone' => 'text-emerald-700 bg-emerald-50',
                'icon' => 'fa-solid fa-user-check',
            ],
            [
                'label' => 'Layanan Peminjaman',
                'value' => $stats['peminjaman_aktif'] > 0 ? 'Berjalan' : 'Siap',
                'tone' => 'text-blue-700 bg-blue-50',
                'icon' => 'fa-solid fa-book-open-reader',
            ],
            [
                'label' => 'Aduan Masyarakat',
                'value' => $stats['aduan_baru'] > 0 ? 'Dipantau' : 'Aman',
                'tone' => $stats['aduan_baru'] > 0 ? 'text-red-700 bg-red-50' : 'text-emerald-700 bg-emerald-50',
                'icon' => 'fa-solid fa-headset',
            ],
        ];
    }

    /**
     * @param  array{total_anggota: int, koleksi_buku: int, peminjaman_aktif: int, aduan_baru: int}  $stats
     * @return array<int, array{title: string, description: string, icon: string}>
     */
    private function todayPriorities(array $stats): array
    {
        return [
            [
                'title' => 'Tinjau Aduan Baru',
                'description' => $stats['aduan_baru'].' aduan menunggu perhatian admin.',
                'icon' => 'fa-regular fa-message',
            ],
            [
                'title' => 'Pantau Peminjaman Aktif',
                'description' => $stats['peminjaman_aktif'].' transaksi sedang berjalan.',
                'icon' => 'fa-solid fa-handshake',
            ],
            [
                'title' => 'Perbarui Koleksi',
                'description' => 'Pastikan data buku dan eksemplar tetap akurat.',
                'icon' => 'fa-solid fa-box-archive',
            ],
        ];
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

        return view(
            'dashboard.koleksi',
            compact(
                'stats',
                'categories',
                'books'
            )
        );
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
