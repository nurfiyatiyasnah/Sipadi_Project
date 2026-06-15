<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_anggota' => 125,
            'koleksi_buku' => Buku::count(),
            'peminjaman_aktif' => EksemplarBuku::where('status_eksemplar', 'Dipinjam')->count(),
            'aduan_baru' => 3,
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
            ['label' => 'Tambah Buku', 'url' => route('books.create')],
            ['label' => 'Tambah Anggota', 'url' => '#'],
            ['label' => 'Kelola Peminjaman', 'url' => '#'],
            ['label' => 'Kelola Berita', 'url' => '#'],
            ['label' => 'Lihat Laporan', 'url' => route('admin.dashboard.koleksi.export')],
        ];

        return view('admin.dashboard', compact('stats', 'aktivitas_terkini', 'aksi_cepat'));
    }

    public function koleksi(Request $request): View
    {
        $query = Buku::query()->with('kategori')->withCount('eksemplar');

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->integer('kategori'));
        }

        if ($request->filled('status')) {
            $query->where('status_katalog', $request->status);
        }

        $totalBuku = Buku::count();
        $tersedia = Buku::where('status_katalog', 'Tersedia')->count();

        $stats = [
            'judul' => $totalBuku,
            'eksemplar' => EksemplarBuku::count(),
            'dipinjam' => EksemplarBuku::where('status_eksemplar', 'Dipinjam')->count(),
            'tersedia' => $tersedia,
            'persen' => round($tersedia / max($totalBuku, 1) * 100, 1),
        ];

        $categories = KategoriBuku::orderBy('nama_kategori')->get();
        $books = $query->paginate(10);

        return view('admin.books.koleksi', compact('stats', 'categories', 'books'));
    }

    public function export()
    {
        $books = Buku::with('kategori')->get();

        $csv = "Judul,ISBN,Penulis,Kategori,Status\n";

        foreach ($books as $book) {
            $csv .= "\"{$book->judul}\",";
            $csv .= "\"{$book->isbn}\",";
            $csv .= "\"{$book->penulis}\",";
            $csv .= '"'.($book->kategori?->nama_kategori ?? '').'",';
            $csv .= "\"{$book->status_katalog}\"\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="koleksi_buku.csv"');
    }
}
