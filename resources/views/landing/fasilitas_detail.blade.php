<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $fasilita->nama_fasilitas }} - Fasilitas SIPADI</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f5e9] font-sans text-[#061b3a] antialiased">
    @include('layouts.public_navbar')

    <!-- Breadcrumb -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 py-6">
        <nav class="flex min-w-0 text-sm font-semibold text-slate-500" aria-label="Breadcrumb">
            <ol class="inline-flex min-w-0 items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('landing') }}" class="inline-flex items-center hover:text-[#04241e] transition">
                        Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-xs mx-2 text-slate-400"></i>
                        <a href="{{ route('fasilitas.public.index') }}" class="hover:text-[#04241e] transition">Fasilitas</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex min-w-0 items-center">
                        <i class="fa-solid fa-chevron-right text-xs mx-2 text-slate-400"></i>
                        <span class="truncate text-[#04241e] font-bold">{{ $fasilita->nama_fasilitas }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Main Content Area -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 pb-16">
        <div class="grid min-w-0 grid-cols-1 gap-8 lg:grid-cols-[1fr_380px]">
            
            <!-- Left Content: Image and Details -->
            <div class="min-w-0 space-y-8">
                <!-- Main Image -->
                <div class="relative min-w-0 overflow-hidden rounded-3xl bg-white shadow-sm border border-slate-100">
                    @if($fasilita->gambar)
                        <img src="{{ asset('storage/' . $fasilita->gambar) }}" alt="{{ $fasilita->nama_fasilitas }}" class="w-full h-auto object-cover max-h-[500px]">
                    @else
                        <div class="w-full h-80 bg-slate-100 flex items-center justify-center">
                            <i class="fa-regular fa-image text-6xl text-slate-300"></i>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="inline-flex items-center rounded-full bg-white/90 backdrop-blur px-3 py-1.5 text-xs font-bold text-[#04241e] shadow-sm">
                            <i class="fa-solid fa-tag mr-1.5 text-slate-400"></i> {{ $fasilita->kategori }}
                        </span>
                    </div>
                </div>

                <!-- Info Cards (Mobile visible, Desktop hidden) -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:hidden">
                    <div class="min-w-0 rounded-2xl bg-white p-4 shadow-sm border border-slate-100 flex flex-col items-center justify-center text-center">
                        <i class="fa-solid fa-location-dot text-emerald-600 text-xl mb-2"></i>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lokasi</span>
                        <span class="mt-1 break-words text-sm font-bold text-[#04241e]">{{ $fasilita->lokasi }}</span>
                    </div>
                    <div class="min-w-0 rounded-2xl bg-white p-4 shadow-sm border border-slate-100 flex flex-col items-center justify-center text-center">
                        <i class="fa-solid fa-users text-emerald-600 text-xl mb-2"></i>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kapasitas</span>
                        <span class="mt-1 break-words text-sm font-bold text-[#04241e]">{{ $fasilita->jumlah_unit }} {{ $fasilita->satuan_kapasitas ?? 'Orang' }}</span>
                    </div>
                </div>

                <!-- Description -->
                <div class="min-w-0 rounded-3xl bg-white p-8 shadow-sm border border-slate-100">
                    <h1 class="mb-6 break-words font-serif text-3xl font-bold text-[#04241e]">{{ $fasilita->nama_fasilitas }}</h1>
                    
                    <div class="prose prose-slate max-w-none break-words text-sm lg:text-base leading-loose text-slate-600">
                        {!! nl2br(e($fasilita->deskripsi)) !!}
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="min-w-0 space-y-6">
                <!-- Status & Quick Info -->
                <div class="min-w-0 rounded-3xl bg-[#04241e] p-8 text-white shadow-xl">
                    <h3 class="font-bold text-xl mb-6">Informasi Fasilitas</h3>
                    
                    <div class="space-y-5">
                        <div class="flex justify-between items-center pb-5 border-b border-white/10">
                            <span class="text-slate-300 text-sm">Status</span>
                            @if(in_array(strtolower($fasilita->status_fasilitas), ['tersedia', 'aktif']))
                                <span class="inline-flex items-center rounded-full bg-emerald-500/20 px-2.5 py-1 text-xs font-bold text-emerald-400 border border-emerald-500/30">
                                    <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                    Tersedia
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-red-500/20 px-2.5 py-1 text-xs font-bold text-red-400 border border-red-500/30">
                                    <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-red-400"></span>
                                    Tidak Tersedia
                                </span>
                            @endif
                        </div>

                        <div class="flex min-w-0 justify-between items-center gap-4 pb-5 border-b border-white/10">
                            <span class="text-slate-300 text-sm">Lokasi</span>
                            <span class="min-w-0 break-words text-sm font-bold text-white text-right">{{ $fasilita->lokasi }}</span>
                        </div>

                        <div class="flex min-w-0 justify-between items-center gap-4 pb-5 border-b border-white/10">
                            <span class="text-slate-300 text-sm">Kapasitas</span>
                            <span class="min-w-0 break-words text-sm font-bold text-white text-right">{{ $fasilita->jumlah_unit }} {{ $fasilita->satuan_kapasitas ?? 'Orang' }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <a href="{{ route('fasilitas.public.index') }}" class="flex w-full items-center justify-center rounded-xl bg-white/10 px-4 py-3 text-sm font-bold text-white hover:bg-white/20 transition">
                            Kembali ke Daftar Fasilitas
                        </a>
                    </div>
                </div>

                <!-- Assistance Card -->
                <div class="min-w-0 rounded-3xl bg-white p-8 shadow-sm border border-slate-100 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 mb-4">
                        <i class="fa-solid fa-headset text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-[#04241e] mb-2">Butuh Bantuan?</h3>
                    <p class="text-sm text-slate-500 mb-6">Jika Anda memiliki pertanyaan seputar fasilitas ini, silakan hubungi petugas kami.</p>
                    <a href="{{ route('landing') }}#kontak" class="inline-block rounded-full bg-[#ffdc7c] px-6 py-2.5 text-sm font-bold text-[#04241e] hover:bg-[#ffe399] transition shadow-md shadow-[#ffdc7c]/20">
                        Hubungi Kami
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 mt-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
            <div class="flex flex-col sm:flex-row justify-between text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} Dinas Perpustakaan dan Kearsipan Kota Bukittinggi. Seluruh Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>
</body>
</html>
