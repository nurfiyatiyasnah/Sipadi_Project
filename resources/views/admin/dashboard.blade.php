@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Anggota -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="text-3xl font-bold text-gray-900"> {{ number_format($stats['total_anggota'] ?? 0) }} </div>

                <span class="text-green-600 text-sm font-semibold flex items-center gap-1">
                    <i class="fas fa-arrow-up"></i> +12%
                </span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">TOTAL ANGGOTA</p>
                </div>
            </div>
        </div>

        <!-- Koleksi Buku -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['koleksi_buku'] ?? 0) }}</div>
                <div class="flex items-center">
                    <i class="fas fa-shield text-gray-400 text-lg"></i>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">KOLEKSI BUKU</p>
                    <p class="text-xs text-gray-500">Terverifikasi</p>
                </div>
            </div>
        </div>

        <!-- Peminjaman -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['peminjaman_aktif'] ?? 0) }}</div>
                <div class="flex items-center">
                    <i class="fas fa-gem text-yellow-400 text-lg"></i>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-handshake text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">PEMINJAMAN</p>
                </div>
            </div>
        </div>

        <!-- Aduan Baru -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['aduan_baru'] ?? 0 }}</div>
                <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs font-semibold">URGENT</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">ADUAN BARU</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Aktivitas Terkini -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Aktivitas Terkini</h3>
                    <a href="#" class="text-yellow-500 hover:text-yellow-600 text-sm font-semibold flex items-center gap-1">
                        Lihat Semua
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach ($aktivitas_terkini as $aktivitas)
                        <div class="flex gap-4 pb-4 border-b border-gray-200 last:border-b-0">
                            <div class="flex-shrink-0 w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                @if ($aktivitas['icon'] === 'book')
                                    <i class="fas fa-book text-gray-600"></i>
                                @elseif ($aktivitas['icon'] === 'user')
                                    <i class="fas fa-user text-gray-600"></i>
                                @else
                                    <i class="fas fa-box text-gray-600"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 text-sm">{{ $aktivitas['judul'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $aktivitas['deskripsi'] }}</p>
                                @if ($aktivitas['status'])
                                    <span class="inline-block mt-2 px-2 py-1 bg-green-100 text-green-700 text-xs rounded font-medium">
                                        {{ $aktivitas['status'] }}
                                    </span>
                                @endif
                                <p class="text-xs text-gray-400 mt-2">{{ $aktivitas['waktu'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Aksi Cepat & Tren -->
        <div class="space-y-6">
            <!-- Aksi Cepat -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Aksi Cepat</h3>
                <div class="space-y-3">
                    @foreach ($aksi_cepat as $aksi)
                        <button class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition group">
                            <span class="text-gray-900 font-medium text-sm">{{ $aksi['label'] }}</span>
                            <i class="fas fa-arrow-right text-gray-400 group-hover:text-gray-600"></i>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Tren Peminjaman -->
            <div class="bg-gray-900 text-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold">Tren Peminjaman</h3>
                    <i class="fas fa-chart-line text-yellow-400"></i>
                </div>
                <p class="text-xs text-gray-400 mb-4">7 Hari Terakhir</p>
                <div class="flex items-end justify-between h-24 gap-2">
                    <div class="flex-1 bg-yellow-400 rounded-t h-12 opacity-75"></div>
                    <div class="flex-1 bg-yellow-400 rounded-t h-16 opacity-85"></div>
                    <div class="flex-1 bg-yellow-400 rounded-t h-14 opacity-75"></div>
                    <div class="flex-1 bg-yellow-400 rounded-t h-20"></div>
                    <div class="flex-1 bg-yellow-400 rounded-t h-12 opacity-75"></div>
                    <div class="flex-1 bg-yellow-400 rounded-t h-18 opacity-80"></div>
                    <div class="flex-1 bg-yellow-400 rounded-t h-10 opacity-70"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-400 mt-2">
                    <span>SEN</span>
                    <span>SEL</span>
                    <span>RAB</span>
                    <span>KAM</span>
                    <span>JUM</span>
                    <span>SAB</span>
                    <span>MIN</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Program Prioritas -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-lg shadow overflow-hidden">
        <div class="flex flex-col md:flex-row items-stretch">
            <div class="flex-1 p-8 text-white">
                <span class="inline-block px-3 py-1 bg-yellow-400 text-gray-900 text-xs font-bold rounded mb-4">
                    PROGRAM PRIORITAS
                </span>
                <h3 class="text-2xl font-bold mb-2">Digitalisasi Manuskript Kuno Bukittinggi</h3>
                <p class="text-gray-300 text-sm">
                    Mendokumentasikan sejarah kota untuk generasi mendatang. Proyek ini akan membantu pelestarian warisan budaya lokal.
                </p>
            </div>
            <div class="hidden md:block w-80 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=400&h=300&fit=crop'); opacity: 0.8;"></div>
        </div>
    </div>
</div>
@endsection
