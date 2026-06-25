<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPADI Bukittinggi - Perpustakaan & Arsip Digital</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f5e9] font-sans text-[#061b3a] antialiased">

    <!-- Header / Navbar -->
    <header class="bg-[#04241e] text-white">
        <div class="mx-auto max-w-7xl px-6 py-5 lg:px-12 flex items-center justify-between">
            <!-- Logo Section -->
            <a href="{{ route('landing') }}" class="flex items-center gap-3 hover:opacity-90 transition">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#ffdc7c] text-[#04241e]">
                    <i class="fa-solid fa-building-columns text-lg"></i>
                </span>
                <span class="font-serif font-bold text-xl tracking-tight">SIPADI Bukittinggi</span>
            </a>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold">
                <a href="{{ route('landing') }}" class="text-[#ffdc7c] border-b-2 border-[#ffdc7c] pb-1">Beranda</a>
                <a href="{{ route('katalog') }}" class="text-slate-300 hover:text-white transition">Katalog</a>
                <a href="#koleksi" class="text-slate-300 hover:text-white transition">Layanan</a>
                <a href="#kontak" class="text-slate-300 hover:text-white transition">Fasilitas</a>
                <a href="{{ route('berita.public.index') }}" class="text-slate-300 hover:text-white transition">Berita</a>
                <a href="#agenda" class="text-slate-300 hover:text-white transition">Agenda</a>
            </nav>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4">
                @auth
                    @if (Auth::user()->isPetugas())
                        <a href="{{ route('petugas.dashboard') }}" class="rounded-xl bg-[#ffdc7c] px-5 py-2.5 text-sm font-bold text-[#04241e] hover:bg-[#ffe399] transition shadow-md shadow-[#ffdc7c]/10">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('anggota.e-kartu') }}" class="rounded-xl bg-[#ffdc7c] px-5 py-2.5 text-sm font-bold text-[#04241e] hover:bg-[#ffe399] transition shadow-md shadow-[#ffdc7c]/10">
                            E-Kartu
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-xl border border-slate-500 px-4 py-2.5 text-sm font-semibold hover:bg-white/10 transition text-white" title="Profil Saya">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#ffdc7c] text-[#04241e]">
                            <i class="fa-regular fa-user text-xs"></i>
                        </span>
                        <span class="max-w-[150px] truncate">{{ Auth::user()->nama }}</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="rounded-xl border border-slate-500 px-5 py-2.5 text-sm font-semibold hover:bg-white/10 transition">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl border border-slate-500 px-5 py-2.5 text-sm font-semibold hover:bg-white/10 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-[#ffdc7c] px-5 py-2.5 text-sm font-bold text-[#04241e] hover:bg-[#ffe399] transition shadow-md shadow-[#ffdc7c]/10">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero / Banner Section -->
    <section class="bg-[#04241e] text-white py-12 lg:py-20 overflow-hidden relative">
        <!-- Subtle Pattern Overlay -->
        <div class="absolute inset-0 opacity-10 pointer-events-none mix-blend-overlay bg-[radial-gradient(circle_at_center,_#ffffff_0,_transparent_70%)]"></div>

        <div class="mx-auto max-w-7xl px-6 lg:px-12 grid gap-12 lg:grid-cols-2 items-center relative z-10">
            <!-- Left Side: Slider Preview Card -->
            <div class="relative flex items-center">
                <!-- Floating Left Arrow Button -->
                <button class="absolute -left-6 z-20 flex h-12 w-12 items-center justify-center rounded-full bg-[#ffdc7c] text-[#04241e] hover:bg-[#ffe399] shadow-lg transition">
                    <i class="fa-solid fa-chevron-left text-lg"></i>
                </button>

                <!-- Slider Card Content -->
                <div class="w-full bg-white text-[#061b3a] rounded-[2.5rem] p-6 shadow-2xl transition duration-300 hover:scale-[1.01]">
                    <div class="overflow-hidden rounded-3xl relative">
                        <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1200&q=80" alt="Literasi Digital" class="h-60 w-full object-cover">
                    </div>
                    <div class="mt-6">
                        <h3 class="font-serif text-2xl font-bold leading-tight text-[#04241e]">
                            Literasi Digital dan Arsip Daerah: Inovasi Baru SIPADI untuk Bukittinggi
                        </h3>
                        <p class="mt-3 text-slate-500 text-sm leading-relaxed">
                            SIPADI menghadirkan layanan perpustakaan dan arsip digital yang lebih modern, memudahkan masyarakat mengakses buku, dokumen, dan information literasi secara terpadu.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Heading Content -->
            <div class="flex flex-col justify-center items-start lg:pl-6 relative">
                <!-- Floating Right Arrow Button -->
                <button class="absolute -right-4 -top-12 lg:-top-16 z-20 flex h-12 w-12 items-center justify-center rounded-full bg-[#ffdc7c] text-[#04241e] hover:bg-[#ffe399] shadow-lg transition">
                    <i class="fa-solid fa-chevron-right text-lg"></i>
                </button>

                <h1 class="font-serif text-5xl lg:text-6xl font-bold leading-[1.1] tracking-tight">
                    Literasi Digital<br>dan Arsip Daerah:<br>
                    <span class="text-[#ffdc7c]">Inovasi Baru SIPADI</span>
                </h1>
                <p class="mt-6 text-slate-300 text-lg leading-relaxed max-w-xl">
                    Menghubungkan sejarah masa lalu dengan teknologi masa depan untuk kemajuan literasi masyarakat Bukittinggi.
                </p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="relative z-20 -mt-8">
        <div class="mx-auto max-w-7xl px-6 lg:px-12">
            <div class="grid gap-6 md:grid-cols-3">
                <!-- Stat Card 1 -->
                <div class="bg-white rounded-3xl p-6 text-center shadow-lg border border-slate-100 hover:shadow-xl transition duration-300">
                    <p class="text-4xl font-extrabold text-[#061b3a] font-sans">45,280+</p>
                    <p class="mt-2 text-xs font-bold uppercase tracking-widest text-slate-400">Total Koleksi Buku</p>
                </div>
                <!-- Stat Card 2 -->
                <div class="bg-white rounded-3xl p-6 text-center shadow-lg border border-slate-100 hover:shadow-xl transition duration-300">
                    <p class="text-4xl font-extrabold text-[#061b3a] font-sans">12,150+</p>
                    <p class="mt-2 text-xs font-bold uppercase tracking-widest text-slate-400">Anggota Aktif</p>
                </div>
                <!-- Stat Card 3 -->
                <div class="bg-white rounded-3xl p-6 text-center shadow-lg border border-slate-100 hover:shadow-xl transition duration-300">
                    <p class="text-4xl font-extrabold text-[#061b3a] font-sans">8,400+</p>
                    <p class="mt-2 text-xs font-bold uppercase tracking-widest text-slate-400">Arsip Digital</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pilihan Koleksi Section -->
    <section id="koleksi" class="py-16 max-w-7xl mx-auto px-6 lg:px-12">
        <!-- Section Header -->
        <div class="flex items-end justify-between border-b border-slate-200/60 pb-6 mb-10">
            <div>
                <h2 class="font-serif text-3xl lg:text-4xl font-bold text-[#061b3a]">Pilihan Koleksi</h2>
                <p class="text-slate-500 mt-2 text-sm">Kurasi literatur terbaik minggu ini.</p>
            </div>
            <a href="{{ route('katalog') }}" class="group text-sm font-bold text-[#04241e] hover:underline flex items-center gap-1.5 transition">
                Lihat Semua
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition duration-150"></i>
            </a>
        </div>

        <!-- Cards Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Book 1 -->
            <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
                <div>
                    <!-- Styled Book Cover -->
                    <div class="relative w-full h-[240px] rounded-2xl bg-[#2e4031] flex flex-col justify-between p-5 text-white shadow-sm overflow-hidden mb-4">
                        <div class="absolute left-0 top-0 bottom-0 w-3.5 bg-gradient-to-r from-black/25 to-transparent"></div>
                        <div class="absolute top-3 right-3 bg-emerald-500/90 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                            Tersedia
                        </div>
                        <div class="mt-4 pl-3">
                            <p class="font-serif font-bold text-base leading-snug mt-1">Tamboko Alam Minangkabau</p>
                        </div>
                        <p class="text-xs text-emerald-200 pl-3">A.A. Navis</p>
                    </div>

                    <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 rounded px-2 py-0.5">
                        Sejarah
                    </span>
                    <h4 class="mt-2.5 font-bold text-[#061b3a] line-clamp-1">Tamboko Alam Minangkabau</h4>
                    <p class="text-xs text-slate-500 mt-1">A.A. Navis</p>
                </div>
                <button class="w-full mt-5 border border-[#061b3a] text-[#061b3a] font-bold rounded-xl py-2 px-4 hover:bg-slate-50 transition duration-200 text-sm">
                    Pinjam Buku
                </button>
            </div>

            <!-- Book 2 -->
            <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
                <div>
                    <!-- Styled Book Cover -->
                    <div class="relative w-full h-[240px] rounded-2xl bg-[#8c6d58] flex flex-col justify-between p-5 text-white shadow-sm overflow-hidden mb-4">
                        <div class="absolute left-0 top-0 bottom-0 w-3.5 bg-gradient-to-r from-black/25 to-transparent"></div>
                        <div class="absolute top-3 right-3 bg-slate-500/90 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                            Antrean
                        </div>
                        <div class="mt-4 pl-3">
                            <p class="font-serif font-bold text-base leading-snug mt-1">Sitti Nurbaya</p>
                        </div>
                        <p class="text-xs text-amber-200 pl-3">Marah Roesli</p>
                    </div>

                    <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 rounded px-2 py-0.5">
                        Sastra
                    </span>
                    <h4 class="mt-2.5 font-bold text-[#061b3a] line-clamp-1">Sitti Nurbaya: Kasih Tak Sampai</h4>
                    <p class="text-xs text-slate-500 mt-1">Marah Roesli</p>
                </div>
                <button class="w-full mt-5 bg-blue-50 text-blue-700 font-bold rounded-xl py-2 px-4 hover:bg-blue-100 transition duration-200 text-sm">
                    Masuk Antrean
                </button>
            </div>

            <!-- Book 3 -->
            <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
                <div>
                    <!-- Styled Book Cover -->
                    <div class="relative w-full h-[240px] rounded-2xl bg-[#3f5b7a] flex flex-col justify-between p-5 text-white shadow-sm overflow-hidden mb-4">
                        <div class="absolute left-0 top-0 bottom-0 w-3.5 bg-gradient-to-r from-black/25 to-transparent"></div>
                        <div class="mt-4 pl-3">
                            <p class="font-serif font-bold text-base leading-snug mt-1">Arsitektur Digital Masa Depan</p>
                        </div>
                        <p class="text-xs text-blue-200 pl-3">Tim Peneliti</p>
                    </div>

                    <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 rounded px-2 py-0.5">
                        Teknologi
                    </span>
                    <h4 class="mt-2.5 font-bold text-[#061b3a] line-clamp-1">Arsitektur Digital Masa Depan</h4>
                    <p class="text-xs text-slate-500 mt-1">Tim Peneliti</p>
                </div>
                <button class="w-full mt-5 border border-[#061b3a] text-[#061b3a] font-bold rounded-xl py-2 px-4 hover:bg-slate-50 transition duration-200 text-sm">
                    Pinjam Buku
                </button>
            </div>

            <!-- Explore Card -->
            <div class="bg-[#04241e] text-white rounded-[2rem] p-6 shadow-xl flex flex-col justify-between items-center text-center">
                <div class="mt-8 flex flex-col items-center">
                    <span class="flex h-16 w-16 items-center justify-center rounded-full bg-white/10 text-[#ffdc7c] text-3xl mb-5">
                        <i class="fa-solid fa-compass"></i>
                    </span>
                    <h3 class="font-serif text-xl font-bold leading-snug px-3">
                        Jelajahi 24K+ Koleksi Lainnya
                    </h3>
                </div>
                <a href="{{ route('katalog') }}" class="w-full bg-[#ffdc7c] text-[#04241e] font-bold rounded-2xl py-3.5 hover:bg-[#ffe399] transition duration-200 text-sm flex items-center justify-center gap-2 mt-8">
                    Buka Katalog
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Berita Terbaru & Agenda Section -->
    <section class="py-12 bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 grid gap-8 lg:grid-cols-[1fr_400px]">

            <!-- Berita Terbaru (Left Column) -->
            <div id="berita">
                <div class="flex items-end justify-between border-b border-slate-200/60 pb-5 mb-8">
                    <h2 class="font-serif text-3xl font-bold text-[#061b3a]">Berita Terbaru</h2>
                    <a href="{{ route('berita.public.index') }}" class="text-sm font-bold text-[#04241e] hover:underline">Lihat Semua</a>
                </div>

                <div class="grid gap-4">
                    @forelse ($beritaList as $item)
                        <div class="bg-white rounded-3xl p-4 flex flex-col sm:flex-row gap-5 border border-slate-100 shadow-sm hover:shadow-md transition duration-200">
                            <div class="w-full sm:w-[160px] h-[100px] bg-slate-100 rounded-2xl flex-shrink-0 flex items-center justify-center text-slate-400 overflow-hidden">
                                @if ($item->gambar)
                                    <a href="{{ route('berita.public.show', $item->slug) }}" class="h-full w-full">
                                        <img src="{{ Storage::url($item->gambar) }}" alt="{{ $item->judul }}" class="h-full w-full object-cover rounded-2xl">
                                    </a>
                                @else
                                    <a href="{{ route('berita.public.show', $item->slug) }}" class="flex h-full w-full items-center justify-center">
                                        <i class="fa-regular fa-image text-3xl"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="flex flex-col justify-between py-1">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider
                                        @if($item->kategoriBerita?->nama_kategori === 'Kegiatan')
                                            text-emerald-600
                                        @elseif($item->kategoriBerita?->nama_kategori === 'Pengumuman')
                                            text-blue-600
                                        @else
                                            text-amber-600
                                        @endif">
                                        {{ $item->kategoriBerita?->nama_kategori }}
                                    </span>
                                    <h4 class="mt-1.5 font-bold text-[#061b3a] text-lg leading-snug hover:text-[#04241e] transition">
                                        <a href="{{ route('berita.public.show', $item->slug) }}">{{ $item->judul }}</a>
                                    </h4>
                                </div>
                                <p class="text-xs text-slate-400 mt-2">
                                    {{ ($item->tanggal_terbit ?? $item->created_at)->locale('id')->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm text-center">
                            <p class="text-sm font-semibold text-slate-400">Belum ada berita terbaru.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Agenda (Right Column) -->
            <div id="agenda" class="bg-[#04241e] text-white rounded-[2.5rem] p-6 lg:p-8 shadow-xl flex flex-col justify-between">
                <div>
                    <h3 class="font-serif text-2xl font-bold tracking-wide">Agenda</h3>

                    <div class="mt-8 space-y-6">
                        @forelse ($agendaList as $agenda)
                            <a href="{{ route('agenda.show', $agenda->slug) }}" class="flex gap-4 items-center group">
                                <div class="bg-white/10 rounded-2xl p-2.5 text-center min-w-[60px] group-hover:bg-white/20 transition">
                                    <p class="text-xl font-extrabold text-[#ffdc7c] leading-none">
                                        {{ $agenda->tanggal_mulai ? $agenda->tanggal_mulai->format('d') : '00' }}
                                    </p>
                                    <p class="text-[9px] font-bold uppercase text-slate-300 mt-1">
                                        {{ $agenda->tanggal_mulai ? $agenda->tanggal_mulai->locale('id')->translatedFormat('M') : 'AGEN' }}
                                    </p>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-sm leading-snug truncate group-hover:text-[#ffdc7c] transition">
                                        {{ $agenda->judul_event }}
                                    </h4>
                                    <p class="text-xs text-slate-400 mt-0.5 truncate">
                                        <i class="fa-solid fa-location-dot text-[10px] mr-1"></i>
                                        @if(filter_var($agenda->lokasi, FILTER_VALIDATE_URL))
                                            Tautan Peta
                                        @else
                                            {{ $agenda->lokasi }}
                                        @endif
                                    </p>
                                </div>
                            </a>
                        @empty
                            <p class="text-xs text-slate-400">Tidak ada agenda mendatang saat ini.</p>
                        @endforelse
                    </div>
                </div>

                <a href="{{ route('agenda.index') }}" class="w-full bg-white text-[#04241e] font-bold rounded-2xl py-3.5 hover:bg-slate-100 transition duration-200 text-sm flex items-center justify-center gap-2 mt-8 shadow-md">
                    <i class="fa-regular fa-calendar-days text-sm"></i>
                    Lihat Kalender Lengkap
                </a>
            </div>
        </div>
    </section>

    <!-- Kontak & Map Section -->
    <section id="kontak" class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
        <div class="bg-white rounded-[2.5rem] overflow-hidden border border-slate-100 shadow-lg grid gap-0 lg:grid-cols-2">
            <!-- Left: Kontak Info -->
            <div class="p-8 lg:p-12 flex flex-col justify-between">
                <div>
                    <h3 class="font-serif text-3xl font-bold text-[#061b3a] flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <i class="fa-solid fa-address-book text-lg"></i>
                        </span>
                        Kontak Kami
                    </h3>

                    <div class="mt-8 space-y-6">
                        <!-- Alamat -->
                        <div class="flex gap-4 items-start">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 text-slate-400 mt-0.5">
                                <i class="fa-solid fa-map-location-dot text-sm"></i>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Alamat</p>
                                <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                                    Jl. Perwira No. 7, Belakang Balok, Kec. Aur Birugo Tigo Baleh, Kota Bukittinggi, Sumatera Barat 26138
                                </p>
                                <a href="https://maps.app.goo.gl/hsQ2hDQgA3M7GGLy5" target="_blank" class="mt-2 inline-flex items-center text-xs font-semibold text-emerald-600 hover:text-emerald-700 gap-1 hover:underline">
                                    Buka di Google Maps
                                    <i class="fa-solid fa-up-right-from-square text-[10px]"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Telepon -->
                        <div class="flex gap-4 items-start">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 text-slate-400 mt-0.5">
                                <i class="fa-solid fa-phone text-sm"></i>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Telepon</p>
                                <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                                    (0752) 123456
                                </p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex gap-4 items-start">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 text-slate-400 mt-0.5">
                                <i class="fa-solid fa-envelope text-sm"></i>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Email</p>
                                <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                                    perpustakaan@bukittinggikota.go.id
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Interactive Google Map -->
            <div class="relative h-[300px] lg:h-auto min-h-[300px] bg-slate-100 group">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.7018317765103!2d100.36830871524874!3d-0.31568289977051493!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2fd538c1b3639d9f%3A0x2d510a9f7ea4cb62!2sDinas%20Perpustakaan%20dan%20Kearsipan%20Kota%20Bukittinggi!5e0!3m2!1sid!2sid!4v1719232338100!5m2!1sid!2sid"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="absolute inset-0"></iframe>
                <!-- Hover Link Button -->
                <a href="https://maps.app.goo.gl/hsQ2hDQgA3M7GGLy5" target="_blank" class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm hover:bg-white text-[#061b3a] font-bold text-xs px-3.5 py-2 rounded-xl shadow-md border border-slate-100 flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-map-location-dot"></i>
                    Buka Google Maps
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
            <div class="flex flex-col lg:flex-row justify-between items-start gap-8">
                <!-- Branding -->
                <div>
                    <a href="{{ route('landing') }}" class="flex items-center gap-3 hover:opacity-90 transition">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#04241e] text-[#ffdc7c]">
                            <i class="fa-solid fa-building-columns text-sm"></i>
                        </span>
                        <span class="font-serif font-bold text-lg text-[#04241e] tracking-tight">SIPADI</span>
                    </a>
                    <p class="mt-4 text-sm text-slate-500 max-w-sm leading-relaxed">
                        Sistem Informasi Perpustakaan dan Arsip Digital Terintegrasi Kota Bukittinggi. Menghubungkan masyarakat dengan sumber pengetahuan tanpa batas.
                    </p>
                </div>

                <!-- Footer Navigation -->
                <div class="flex flex-wrap gap-x-8 gap-y-4 text-sm font-semibold text-slate-600">
                    <a href="#" class="hover:text-[#04241e] transition">Tentang Kami</a>
                    <a href="#" class="hover:text-[#04241e] transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-[#04241e] transition">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-[#04241e] transition">Peta Situs</a>
                    <a href="#" class="hover:text-[#04241e] transition">Hubungi Kami</a>
                </div>
            </div>

            <!-- Copyright Area -->
            <div class="border-t border-slate-100 mt-8 pt-8 flex flex-col sm:flex-row justify-between text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} Dinas Perpustakaan dan Kearsipan Kota Bukittinggi. Seluruh Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

</body>
</html>
