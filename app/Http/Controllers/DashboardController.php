<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_anggota' => Anggota::count(),
            'koleksi_buku' => Buku::count(),
            'peminjaman_aktif' => Peminjaman::where('status_peminjaman', 'aktif')->count(),
            'aduan_baru' => Aduan::where('status_aduan', 'baru')->count(),

        ];

        $aktivitas_terkini = [
            [
                'icon' => 'book',
                'judul' => 'Buku Baru Ditambahkan',
                'deskripsi' => 'Admin menambahkan koleksi buku baru ke sistem.',
                'status' => 'Selesai',
                'waktu' => '5 menit lalu',
            ],
            [
                'icon' => 'user',
                'judul' => 'Anggota Baru Terdaftar',
                'deskripsi' => 'Pendaftaran anggota perpustakaan berhasil.',
                'status' => null,
                'waktu' => '30 menit lalu',
            ],
            [
                'icon' => 'book',
                'judul' => 'Peminjaman Buku',
                'deskripsi' => 'Buku berhasil dipinjam oleh anggota.',
                'status' => 'Diproses',
                'waktu' => '1 jam lalu',
            ],
        ];

        $aksi_cepat = [

            ['label' => 'Tambah Buku'],
            ['label' => 'Tambah Anggota'],
            ['label' => 'Kelola Peminjaman'],
            ['label' => 'Kelola Berita'],
            ['label' => 'Lihat Laporan'],
        ];

        return view('admin.dashboard', compact('stats', 'aktivitas_terkini', 'aksi_cepat'));
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
