<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_anggota' => 1240,
            'koleksi_buku' => 5600,
            'peminjaman_aktif' => 42,
            'aduan_baru' => 3,
        ];

        $aktivitas_terkini = [
            [
                'icon' => 'book',
                'judul' => 'Pengembalian Buku "Sejarah Minangkabau"',
                'deskripsi' => 'Anggota: Ahmad Fauzi (ID: 8KT-202401)',
                'status' => 'Konfirmasi Baik',
                'waktu' => '10 menit yang lalu',
            ],
            [
                'icon' => 'user',
                'judul' => 'Registrasi Anggota Baru',
                'deskripsi' => 'Sri Aminah (akan bergabung sebagai anggota kategori Umum)',
                'status' => null,
                'waktu' => '1 jam yang lalu',
            ],
            [
                'icon' => 'box',
                'judul' => 'Pembaruan Stok Koleksi',
                'deskripsi' => 'Penambahan 15 eksemplar buku referensi teknis ke Rak A-04',
                'status' => null,
                'waktu' => '3 jam yang lalu',
            ],
        ];

        $aksi_cepat = [
            ['label' => 'Add Book', 'icon' => 'plus', 'link' => '#'],
            ['label' => 'New Loan', 'icon' => 'arrow-right', 'link' => '#'],
            ['label' => 'Set Schedule', 'icon' => 'calendar', 'link' => '#'],
            ['label' => 'Respond', 'icon' => 'check', 'link' => '#'],
        ];

        return view('dashboard.index', compact('stats', 'aktivitas_terkini', 'aksi_cepat'));
    }
}
