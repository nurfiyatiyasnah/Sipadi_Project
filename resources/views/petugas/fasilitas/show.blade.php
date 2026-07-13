@extends('layouts.petugas')

@section('title', 'Detail Fasilitas')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
            <a href="#" class="hover:text-slate-800">SIPADI</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <a href="{{ route('petugas.fasilitas.index') }}" class="hover:text-slate-800">Fasilitas</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="font-semibold text-slate-800">Detail {{ $fasilita->nama_fasilitas }}</span>
        </div>
        <h2 class="text-2xl font-bold text-[#071426]">Detail Fasilitas</h2>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('petugas.fasilitas.edit', $fasilita->id_fasilitas) }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
            <i class="fa-solid fa-pen"></i> Edit Fasilitas
        </a>
        <button class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-[#0e1f30] px-5 text-sm font-medium text-white transition hover:bg-[#1b2e46]">
            <i class="fa-solid fa-print"></i> Cetak Barcode
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content Left -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Hero Section -->
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden relative">
            <!-- Cover Image -->
            <div class="h-80 w-full bg-slate-100 relative">
                @if($fasilita->gambar)
                    <img src="{{ asset('storage/' . $fasilita->gambar) }}" alt="{{ $fasilita->nama_fasilitas }}" class="h-full w-full object-cover">
                @else
                    <div class="flex h-full w-full items-center justify-center bg-slate-200 text-slate-400">
                        <i class="fa-regular fa-image text-6xl"></i>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    @if(in_array(strtolower($fasilita->status_fasilitas), ['tersedia', 'aktif']))
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 backdrop-blur px-3 py-1 text-xs font-bold text-green-700 shadow-sm">
                            <span class="h-2 w-2 rounded-full bg-green-500"></span> TERSEDIA
                        </span>
                    @elseif(strtolower($fasilita->status_fasilitas) === 'digunakan')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 backdrop-blur px-3 py-1 text-xs font-bold text-amber-700 shadow-sm">
                            <span class="h-2 w-2 rounded-full bg-amber-500"></span> DIGUNAKAN
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 backdrop-blur px-3 py-1 text-xs font-bold text-red-700 shadow-sm">
                            <span class="h-2 w-2 rounded-full bg-red-500"></span> MAINTENANCE
                        </span>
                    @endif
                </div>
            </div>

            <!-- Details -->
            <div class="p-8">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                    <span class="inline-block rounded-full bg-[#ffd56b] px-3 py-1 text-xs font-bold uppercase tracking-wider text-[#0e1f30]">
                        {{ $fasilita->kategori ?? 'Umum' }}
                    </span>
                    <div class="text-right">
                        <span class="block text-xs text-slate-500">Kapasitas Maksimal</span>
                        <span class="font-bold text-amber-600">{{ $fasilita->jumlah_unit ?? 0 }} {{ $fasilita->satuan_kapasitas ?? 'Orang' }}</span>
                    </div>
                </div>

                <h1 class="text-2xl font-bold text-[#0e1f30] mb-2">{{ $fasilita->nama_fasilitas }}</h1>
                <p class="text-slate-500 flex items-center gap-2 mb-6 text-sm">
                    <i class="fa-solid fa-location-dot text-amber-500"></i>
                    {{ $fasilita->lokasi ?? 'Lokasi tidak disebutkan' }}
                </p>

                <div class="space-y-4 text-slate-600 text-sm leading-relaxed">
                    <p class="font-semibold text-slate-800">Deskripsi Fasilitas</p>
                    <div class="whitespace-pre-line">{{ $fasilita->deskripsi ?: 'Tidak ada deskripsi tersedia.' }}</div>
                </div>
            </div>
        </div>

        <!-- Fitur & Kelengkapan -->
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm p-8">
            <h3 class="font-semibold text-slate-800 mb-6">Fitur & Kelengkapan</h3>
            
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @php
                    $fasilitasPendukung = is_array($fasilita->kelengkapan) ? $fasilita->kelengkapan : [];
                @endphp
                
                @forelse($fasilitasPendukung as $item)
                    @php
                        // Determine icon based on name
                        $icon = 'fa-check';
                        if (stripos($item, 'wifi') !== false || stripos($item, 'wi-fi') !== false) $icon = 'fa-wifi';
                        elseif (stripos($item, 'ac') !== false || stripos($item, 'pendingin') !== false) $icon = 'fa-wind';
                        elseif (stripos($item, 'proyektor') !== false) $icon = 'fa-video';
                        elseif (stripos($item, 'sound') !== false || stripos($item, 'audio') !== false) $icon = 'fa-volume-high';
                        elseif (stripos($item, 'papan') !== false || stripos($item, 'board') !== false) $icon = 'fa-chalkboard';
                        elseif (stripos($item, 'cctv') !== false || stripos($item, 'kamera') !== false) $icon = 'fa-video';
                    @endphp
                    <div class="flex flex-col items-center justify-center rounded-xl border border-slate-100 bg-slate-50 p-4 text-center">
                        <i class="fa-solid {{ $icon }} text-2xl text-slate-700 mb-3"></i>
                        <span class="text-sm font-semibold text-slate-800">{{ $item }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 col-span-full">Tidak ada informasi kelengkapan.</p>
                @endforelse
            </div>
        </div>

        <!-- Galeri Foto Ruangan -->
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-semibold text-slate-800">Galeri Foto Ruangan</h3>
                <a href="#" class="text-sm font-semibold text-amber-600 hover:text-amber-700">Lihat Semua Foto</a>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @if($fasilita->gambar)
                    <img src="{{ asset('storage/' . $fasilita->gambar) }}" class="h-32 w-full rounded-xl object-cover border border-slate-200" alt="Galeri 1">
                @endif
                <div class="h-32 w-full rounded-xl bg-slate-100 border border-slate-200"></div>
                <div class="h-32 w-full rounded-xl bg-slate-100 border border-slate-200"></div>
                <div class="flex h-32 w-full flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 text-slate-400 hover:bg-slate-100 cursor-pointer transition">
                    <i class="fa-solid fa-camera-retro text-2xl mb-2"></i>
                    <span class="text-xs font-medium">Tambah Foto</span>
                </div>
            </div>
        </div>

        <!-- Bottom Navigation -->
        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <a href="#" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Fasilitas Sebelumnya
            </a>
            <span class="text-xs text-slate-400">
                <i class="fa-regular fa-clock"></i> Terakhir diperbarui: {{ $fasilita->updated_at->format('d M Y, H:i') }}
            </span>
            <a href="#" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition flex items-center gap-2">
                Fasilitas Selanjutnya <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Right Sidebar Content -->
    <div class="space-y-6">
        
        <!-- Status Operasional Info -->
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="bg-slate-50 p-5 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wider">Status Operasional</h3>
            </div>
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-green-600">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <span class="text-sm text-slate-600">Status Saat Ini</span>
                    </div>
                    <span class="font-bold text-green-600">TERBUKA</span>
                </div>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Senin - Jumat</span>
                        <span class="font-medium text-slate-800">08:00 - 16:30</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Sabtu</span>
                        <span class="font-medium text-slate-800">08:00 - 13:00</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Minggu</span>
                        <span class="font-bold text-red-500">Tutup</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Penggunaan -->
        <div class="rounded-xl border border-slate-200 bg-[#0e1f30] shadow-sm text-white overflow-hidden">
            <div class="p-5 border-b border-slate-700/50">
                <h3 class="text-sm font-bold text-slate-300 uppercase tracking-wider">Statistik Penggunaan</h3>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-sm text-slate-300">Okupansi Hari Ini</span>
                        <span class="text-2xl font-bold">85%</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-slate-700 overflow-hidden">
                        <div class="h-full bg-[#ffd56b] w-[85%] rounded-full"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-lg bg-white/5 p-4">
                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 mb-1">Total Booking</span>
                        <span class="text-xl font-bold">1.2k+</span>
                    </div>
                    <div class="rounded-lg bg-white/5 p-4">
                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 mb-1">Rating User</span>
                        <div class="flex items-center gap-1">
                            <span class="text-xl font-bold">4.9</span>
                            <i class="fa-solid fa-star text-[#ffd56b] text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lokasi Gedung Map -->
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="bg-slate-50 p-5 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wider">Lokasi Gedung</h3>
                <i class="fa-regular fa-compass text-amber-500"></i>
            </div>
            <div class="p-2">
                <div class="h-48 w-full rounded-lg bg-slate-100 flex flex-col items-center justify-center border border-slate-200 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, black 1px, transparent 0); background-size: 16px 16px;"></div>
                    <i class="fa-solid fa-map-location-dot text-4xl text-slate-300 mb-2 relative z-10"></i>
                    <div class="rounded bg-white px-3 py-1.5 shadow text-[10px] font-bold text-slate-700 relative z-10 border border-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-red-500"></i> GEDUNG B - LANTAI 2
                    </div>
                </div>
            </div>
        </div>

        <!-- Penanggung Jawab -->
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="bg-slate-50 p-5 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wider">Penanggung Jawab</h3>
            </div>
            <div class="p-6 text-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($fasilita->createdBy->nama_petugas ?? 'Admin') }}&background=0e1f30&color=fff" alt="Avatar" class="mx-auto h-16 w-16 rounded-full border-2 border-slate-200 shadow-sm mb-3">
                <h4 class="font-bold text-slate-800">{{ $fasilita->createdBy->nama_petugas ?? 'Admin Perpustakaan' }}</h4>
                <p class="text-sm text-slate-500 mb-5">Pengelola Fasilitas</p>
                <button class="w-full inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 shadow-sm">
                    <i class="fa-regular fa-envelope"></i> Hubungi Pengelola
                </button>
            </div>
        </div>

    </div>
</div>
@endsection
