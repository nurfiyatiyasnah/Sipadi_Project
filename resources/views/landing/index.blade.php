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
    @include('layouts.public_navbar')


    <!-- Hero / Banner Section -->
    @php
        $totalSlides = $beritaList->count() > 0 ? $beritaList->count() : 1;
    @endphp

    <section x-data="{
        activeSlide: 0,
        totalSlides: {{ $totalSlides }},
        autoplayTimer: null,
        init() {
            if (this.totalSlides > 1) {
                this.startAutoplay();
            }
        },
        startAutoplay() {
            this.stopAutoplay();
            this.autoplayTimer = setInterval(() => {
                this.nextSlide();
            }, 6000);
        },
        stopAutoplay() {
            if (this.autoplayTimer) {
                clearInterval(this.autoplayTimer);
                this.autoplayTimer = null;
            }
        },
        nextSlide() {
            if (this.totalSlides <= 1) return;
            this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
        },
        prevSlide() {
            if (this.totalSlides <= 1) return;
            this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
        },
        goToSlide(index) {
            this.activeSlide = index;
            if (this.totalSlides > 1) this.startAutoplay();
        }
    }"
    @mouseenter="stopAutoplay()"
    @mouseleave="if (totalSlides > 1) startAutoplay()"
    class="bg-[#04241e] text-white py-12 lg:py-20 overflow-hidden relative">

        <!-- Subtle Pattern Overlay -->
        <div class="absolute inset-0 opacity-10 pointer-events-none mix-blend-overlay bg-[radial-gradient(circle_at_center,_#ffffff_0,_transparent_70%)]"></div>

        <div class="relative z-10 mx-auto max-w-7xl overflow-hidden px-6 lg:px-12">
            <div class="relative flex min-h-[420px] min-w-0 items-center">

                <!-- Floating Prev Button (Left Arrow) -->
                @if($totalSlides > 1)
                    <button @click="prevSlide()"
                            aria-label="Slide sebelumnya"
                            class="absolute left-0 sm:-left-6 top-1/2 -translate-y-1/2 z-30 flex h-11 w-11 sm:h-12 sm:w-12 items-center justify-center rounded-full bg-white/10 text-white border border-white/20 hover:bg-[#ffdc7c] hover:text-[#04241e] hover:border-[#ffdc7c] shadow-xl transition-all duration-200 focus:outline-none backdrop-blur-sm">
                        <i class="fa-solid fa-chevron-left text-base sm:text-lg"></i>
                    </button>
                @endif

                <!-- Floating Next Button (Right Arrow) -->
                @if($totalSlides > 1)
                    <button @click="nextSlide()"
                            aria-label="Slide berikutnya"
                            class="absolute right-0 sm:-right-6 top-1/2 -translate-y-1/2 z-30 flex h-11 w-11 sm:h-12 sm:w-12 items-center justify-center rounded-full bg-white/10 text-white border border-white/20 hover:bg-[#ffdc7c] hover:text-[#04241e] hover:border-[#ffdc7c] shadow-xl transition-all duration-200 focus:outline-none backdrop-blur-sm">
                        <i class="fa-solid fa-chevron-right text-base sm:text-lg"></i>
                    </button>
                @endif

                <!-- Slides Wrapper -->
                <div class="min-w-0 w-full">
                    @if($beritaList->isNotEmpty())
                        @foreach($beritaList as $index => $item)
                            @php
                                $imageUrl = $item->gambar
                                    ? (str_starts_with($item->gambar, 'http') ? $item->gambar : Storage::url($item->gambar))
                                    : 'https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1200&q=80';
                                $plainIsi = strip_tags($item->isi ?? '');
                                $shortExcerpt = \Illuminate\Support\Str::limit($plainIsi, 140, '...');
                                $longExcerpt = \Illuminate\Support\Str::limit($plainIsi, 180, '...');
                            @endphp

                            <div x-show="activeSlide === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-500 transform"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-300 transform absolute inset-0 pointer-events-none"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="grid min-w-0 grid-cols-1 gap-8 lg:gap-12 lg:grid-cols-2 items-center w-full">

                                <!-- Left Side: Slider Preview Card -->
                                <div class="relative flex min-w-0 items-center w-full">
                                    <div class="min-w-0 w-full overflow-hidden rounded-[2.5rem] bg-white p-6 text-[#061b3a] shadow-2xl transition duration-300 hover:scale-[1.01]">
                                        <a href="{{ route('berita.public.show', $item->slug) }}" class="block min-w-0 group">
                                            <div class="relative h-60 min-w-0 overflow-hidden rounded-3xl w-full bg-slate-100">
                                                <img src="{{ $imageUrl }}" alt="{{ $item->judul }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-500">
                                                @if($item->kategoriBerita?->nama_kategori)
                                                    <span class="absolute top-3 left-3 bg-[#04241e]/90 text-[#ffdc7c] backdrop-blur-sm text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                                        {{ $item->kategoriBerita->nama_kategori }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="mt-6">
                                                <h3 class="break-words font-serif text-xl sm:text-2xl font-bold leading-tight text-[#04241e] group-hover:text-emerald-700 transition line-clamp-2">
                                                    {{ $item->judul }}
                                                </h3>
                                                <p class="mt-3 break-all text-slate-500 text-sm leading-relaxed line-clamp-3">
                                                    {{ $shortExcerpt }}
                                                </p>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <!-- Right Side: Heading Content -->
                                <div class="relative flex min-w-0 flex-col justify-center items-start overflow-hidden lg:pl-6">
                                    @if($item->kategoriBerita?->nama_kategori)
                                        <span class="text-[#ffdc7c] text-xs font-bold uppercase tracking-widest mb-3 bg-white/10 px-3 py-1 rounded-full border border-white/10">
                                            {{ $item->kategoriBerita->nama_kategori }}
                                        </span>
                                    @endif

                                    <h1 class="break-words font-serif text-3xl sm:text-4xl lg:text-5xl font-bold leading-[1.15] tracking-tight">
                                        <a href="{{ route('berita.public.show', $item->slug) }}" class="hover:text-[#ffdc7c] transition duration-200 line-clamp-3">
                                            {{ $item->judul }}
                                        </a>
                                    </h1>

                                    <p class="mt-5 max-w-xl break-all text-slate-300 text-base sm:text-lg leading-relaxed line-clamp-3">
                                        {{ $longExcerpt }}
                                    </p>

                                    <div class="mt-8 flex items-center gap-4">
                                        <a href="{{ route('berita.public.show', $item->slug) }}" class="inline-flex items-center gap-2 bg-[#ffdc7c] text-[#04241e] font-bold px-6 py-3 rounded-2xl hover:bg-[#ffe399] transition duration-200 text-sm shadow-md">
                                            Baca Selengkapnya
                                            <i class="fa-solid fa-arrow-right text-xs"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    @else
                        <!-- Fallback Hero Default -->
                        <div class="grid min-w-0 grid-cols-1 gap-8 lg:gap-12 lg:grid-cols-2 items-center w-full">
                            <!-- Left Side: Default Card -->
                            <div class="relative flex min-w-0 items-center w-full">
                                <div class="min-w-0 w-full overflow-hidden rounded-[2.5rem] bg-white p-6 text-[#061b3a] shadow-2xl">
                                    <div class="relative h-60 min-w-0 overflow-hidden rounded-3xl w-full bg-slate-100">
                                        <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1200&q=80" alt="SIPADI Bukittinggi" class="h-full w-full object-cover">
                                    </div>
                                    <div class="mt-6">
                                        <h3 class="break-words font-serif text-2xl font-bold leading-tight text-[#04241e]">
                                            Selamat Datang di SIPADI Bukittinggi
                                        </h3>
                                        <p class="mt-3 break-words text-slate-500 text-sm leading-relaxed">
                                            SIPADI hadir untuk memudahkan masyarakat mengakses buku, dokumen, dan informasi literasi melalui layanan perpustakaan dan arsip digital yang terintegrasi dan terpercaya.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side: Default Heading Content -->
                            <div class="relative flex min-w-0 flex-col justify-center items-start overflow-hidden lg:pl-6">
                                <h1 class="break-words font-serif text-4xl lg:text-6xl font-bold leading-[1.1] tracking-tight">
                                    Selamat Datang di<br>
                                    SIPADI Bukittinggi
                                </h1>
                                <p class="mt-4 text-[#ffdc7c] font-serif text-2xl lg:text-3xl font-semibold">
                                    Layanan Digital Perpustakaan dan Arsip
                                </p>
                                <p class="mt-5 max-w-xl break-words text-slate-300 text-base lg:text-lg leading-relaxed">
                                    Akses informasi, buku, dan arsip daerah dalam satu layanan terpadu.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Slider Dot Indicators -->
            @if($totalSlides > 1)
                <div class="mt-8 flex justify-center items-center gap-2 relative z-20">
                    <template x-for="(slide, index) in totalSlides" :key="index">
                        <button @click="goToSlide(index)"
                                :class="activeSlide === index ? 'w-8 bg-[#ffdc7c]' : 'w-2.5 bg-white/30 hover:bg-white/60'"
                                class="h-2.5 rounded-full transition-all duration-300 focus:outline-none"
                                :aria-label="'Buka slide ' + (index + 1)">
                        </button>
                    </template>
                </div>
            @endif

        </div>
    </section>

    <!-- Stats Section -->
    <section class="relative z-20 -mt-8">
        <div class="mx-auto max-w-7xl px-6 lg:px-12">
            <div class="grid gap-6 md:grid-cols-3">
                <!-- Stat Card 1 -->
                <div class="bg-white rounded-3xl p-6 text-center shadow-lg border border-slate-100 hover:shadow-xl transition duration-300">
                    <p class="text-4xl font-extrabold text-[#061b3a] font-sans">{{ number_format($koleksiBuku) }}</p>
                    <p class="mt-2 text-xs font-bold uppercase tracking-widest text-slate-400">Koleksi Buku</p>
                </div>
                <!-- Stat Card 2 -->
                <div class="bg-white rounded-3xl p-6 text-center shadow-lg border border-slate-100 hover:shadow-xl transition duration-300">
                    <p class="text-4xl font-extrabold text-[#061b3a] font-sans">{{ number_format($jumlahBuku) }}</p>
                    <p class="mt-2 text-xs font-bold uppercase tracking-widest text-slate-400">Jumlah Buku</p>
                </div>
                <!-- Stat Card 3 -->
                <div class="bg-white rounded-3xl p-6 text-center shadow-lg border border-slate-100 hover:shadow-xl transition duration-300">
                    <p class="text-4xl font-extrabold text-[#061b3a] font-sans">{{ number_format($anggotaAktif) }}</p>
                    <p class="mt-2 text-xs font-bold uppercase tracking-widest text-slate-400">Anggota Aktif</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pilihan Buku Section -->
    <section id="koleksi" class="py-16 max-w-7xl mx-auto px-6 lg:px-12">
        <!-- Section Header -->
        <div class="flex items-end justify-between border-b border-slate-200/60 pb-6 mb-10">
            <div>
                <h2 class="font-serif text-3xl lg:text-4xl font-bold text-[#061b3a]">Pilihan Buku</h2>
                <p class="text-slate-500 mt-2 text-sm">Koleksi buku terbaru di perpustakaan kami.</p>
            </div>
            <a href="{{ route('katalog') }}" class="group text-sm font-bold text-[#04241e] hover:underline flex items-center gap-1.5 transition">
                Lihat Semua
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition duration-150"></i>
            </a>
        </div>

        <!-- Cards Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($pilihanBuku as $buku)
                @php
                    $tersedia = $buku->eksemplar_tersedia_count > 0;
                    $coverClasses = ['bg-[#2e4031]', 'bg-[#8c6d58]', 'bg-[#3f5b7a]', 'bg-[#5b3a5e]', 'bg-[#4a6741]', 'bg-[#6b4c3b]'];
                    $textColors = ['text-emerald-200', 'text-amber-200', 'text-blue-200', 'text-purple-200', 'text-lime-200', 'text-orange-200'];
                    $colorIndex = $loop->index % count($coverClasses);
                    $coverClass = $coverClasses[$colorIndex];
                @endphp
                <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
                    <div>
                        <!-- Styled Book Cover -->
                        <div class="relative w-full h-[240px] rounded-2xl bg-slate-100 flex flex-col items-center justify-center shadow-sm overflow-hidden mb-4">
                            @if($buku->gambar_cover)
                                @php
                                    $imageUrl = str_starts_with($buku->gambar_cover, 'http') ? $buku->gambar_cover : asset('storage/' . $buku->gambar_cover);
                                @endphp
                                <img src="{{ $imageUrl }}" alt="{{ $buku->judul }}" class="w-full h-full object-cover">
                            @else
                                <!-- Dynamic Fallback Cover -->
                                <div class="absolute inset-0 p-5 text-white flex flex-col justify-between {{ $coverClass }}">
                                    <div class="absolute left-0 top-0 bottom-0 w-3.5 bg-gradient-to-r from-black/25 to-transparent"></div>
                                    <div class="mt-4 pl-3">
                                        <p class="font-serif font-bold text-base leading-snug mt-1 line-clamp-3">{{ $buku->judul }}</p>
                                    </div>
                                    <p class="text-xs {{ $textColors[$colorIndex] }} pl-3">{{ $buku->penulis ?? '-' }}</p>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3 {{ $tersedia ? 'bg-emerald-500/90' : 'bg-slate-500/90' }} backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                {{ $tersedia ? 'Tersedia' : 'Dipinjam' }}
                            </div>
                        </div>

                        <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 rounded px-2 py-0.5">
                            {{ $buku->kategori->nama_kategori ?? 'Umum' }}
                        </span>
                        <h4 class="mt-2.5 font-bold text-[#061b3a] line-clamp-1">{{ $buku->judul }}</h4>
                        <p class="text-xs text-slate-500 mt-1">{{ $buku->penulis ?? '-' }}</p>
                    </div>
                    <a href="{{ route('katalog.show', $buku) }}" class="w-full mt-5 border border-[#061b3a] text-[#061b3a] font-bold rounded-xl py-2 px-4 hover:bg-slate-50 transition duration-200 text-sm text-center block">
                        Lihat Detail
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-slate-400">
                    <i class="fa-solid fa-book-open text-4xl mb-3"></i>
                    <p class="text-sm">Belum ada buku di perpustakaan.</p>
                </div>
            @endforelse

            <!-- Explore Card -->
            <div class="bg-[#04241e] text-white rounded-[2rem] p-6 shadow-xl flex flex-col justify-between items-center text-center">
                <div class="mt-8 flex flex-col items-center">
                    <span class="flex h-16 w-16 items-center justify-center rounded-full bg-white/10 text-[#ffdc7c] text-3xl mb-5">
                        <i class="fa-solid fa-compass"></i>
                    </span>
                    <h3 class="font-serif text-xl font-bold leading-snug px-3">
                        Jelajahi {{ number_format($koleksiBuku) }}+ Koleksi Lainnya
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
        <div class="grid min-w-0 gap-0 overflow-hidden rounded-[2.5rem] border border-slate-100 bg-white shadow-lg lg:grid-cols-2">
            <!-- Left: Kontak Info -->
            <div class="flex min-w-0 flex-col justify-between p-8 lg:p-12">
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
                    <a href="{{ route('tentang') }}" class="hover:text-[#04241e] transition">Tentang Kami</a>
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
