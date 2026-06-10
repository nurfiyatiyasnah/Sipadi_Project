<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_anggota'     => 125,
            'koleksi_buku'      => Book::count(),
            'peminjaman_aktif'  => Book::where('status', 'Dipinjam')->count(),
            'aduan_baru'        => 3,
        ];

        $aktivitas_terkini = [
            [
                'icon' => 'book',
                'judul' => 'Buku Baru Ditambahkan',
                'deskripsi' => 'Admin menambahkan koleksi buku baru ke sistem.',
                'status' => 'Selesai',
                'waktu' => '5 menit lalu'
            ],
            [
                'icon' => 'user',
                'judul' => 'Anggota Baru Terdaftar',
                'deskripsi' => 'Pendaftaran anggota perpustakaan berhasil.',
                'status' => null,
                'waktu' => '30 menit lalu'
            ],
            [
                'icon' => 'book',
                'judul' => 'Peminjaman Buku',
                'deskripsi' => 'Buku berhasil dipinjam oleh anggota.',
                'status' => 'Diproses',
                'waktu' => '1 jam lalu'
            ]
        ];

        $aksi_cepat = [
            ['label' => 'Tambah Buku', 'url' => route('books.create')],
            ['label' => 'Tambah Anggota', 'url' => '#'],
            ['label' => 'Kelola Peminjaman', 'url' => '#'],
            ['label' => 'Kelola Berita', 'url' => '#'],
            ['label' => 'Lihat Laporan', 'url' => route('admin.dashboard.koleksi.export')],
        ];

        // sesuai file: resources/views/admin/dashboard.blade.php
        return view('admin.dashboard', compact('stats','aktivitas_terkini','aksi_cepat'));
    }

    public function koleksi(Request $request)
    {
        $query = Book::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $stats = [
            'judul'     => Book::count(),
            'eksemplar' => Book::sum('stok'),
            'dipinjam'  => Book::where('status', 'Dipinjam')->count(),
            'tersedia'  => Book::where('status', 'Tersedia')->count(),
            'persen'    => round(Book::where('status','Tersedia')->count() / max(Book::count(),1) * 100, 1),
        ];

        $categories = Book::select('kategori')->distinct()->pluck('kategori');
        $books = $query->paginate(10);

        // sesuai file: resources/views/admin/books/koleksi.blade.php
        return view('admin.books.koleksi', compact('stats','categories','books'));
    }

    public function export()
    {
        $books = Book::all();

        $csv = "Judul,ISBN,Penulis,Kategori,Stok,Status\n";

        foreach ($books as $book) {
            $csv .= "\"{$book->judul}\",";
            $csv .= "\"{$book->isbn}\",";
            $csv .= "\"{$book->penulis}\",";
            $csv .= "\"{$book->kategori}\",";
            $csv .= "\"{$book->stok}\",";
            $csv .= "\"{$book->status}\"\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition','attachment; filename=\"koleksi_buku.csv\"');
    }
}
