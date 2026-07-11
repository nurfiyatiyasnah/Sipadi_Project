<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita & Informasi - SIPADI Bukittinggi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f5e9] font-sans text-[#061b3a] antialiased">

    <!-- Header / Navbar -->
    @include('layouts.public_navbar')

    <!-- Page Title Area -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 py-10">
        <h1 class="font-serif text-4xl lg:text-5xl font-bold text-[#04241e]">Berita & Informasi Terkini</h1>
        <p class="text-slate-500 mt-3 text-sm lg:text-base">Ikuti perkembangan terbaru kegiatan dan informasi seputar Perpustakaan Kota Bukittinggi.</p>
    </div>

    <!-- Main Content Area -->
    <div class="mx-auto grid max-w-7xl grid-cols-1 gap-8 px-6 pb-16 lg:grid-cols-[300px_1fr] lg:px-12">
        <!-- Left Sidebar -->
        <aside class="min-w-0 space-y-6">
            <!-- Cari Berita Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <h3 class="font-serif font-bold text-lg text-[#04241e] mb-4">Cari Berita</h3>
                <form action="{{ route('berita.public.index') }}" method="GET" class="relative">
                    @if(request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kata kunci..." 
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-4 pr-10 text-sm text-[#061b3a] focus:border-[#ffdc7c] focus:ring-0 focus:outline-none placeholder:text-slate-400">
                    <button type="submit" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#04241e] transition">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>

            <!-- Kategori Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <h3 class="font-serif font-bold text-lg text-[#04241e] mb-4">Kategori</h3>
                <div class="space-y-3">
                    <a href="{{ route('berita.public.index', array_merge(request()->except(['kategori', 'page']))) }}" 
                       class="flex items-center justify-between text-sm {{ !request('kategori') ? 'font-bold text-[#04241e]' : 'text-slate-600 hover:text-[#04241e]' }} transition">
                        <span>Semua Berita</span>
                        <span class="rounded-full bg-[#04241e] text-white text-xs px-2.5 py-0.5 font-sans font-bold">
                            {{ $totalBeritaCount }}
                        </span>
                    </a>
                    @foreach($kategoriList as $kat)
                        <a href="{{ route('berita.public.index', array_merge(request()->except('page'), ['kategori' => $kat->id_kategori_berita])) }}" 
                           class="flex items-center justify-between text-sm {{ request('kategori') == $kat->id_kategori_berita ? 'font-bold text-[#04241e]' : 'text-slate-600 hover:text-[#04241e]' }} transition">
                            <span>{{ $kat->nama_kategori }}</span>
                            <span class="rounded-full bg-slate-100 text-slate-600 text-xs px-2.5 py-0.5 font-sans font-semibold">
                                {{ $kat->berita_count }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Tag Populer Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <h3 class="font-serif font-bold text-lg text-[#04241e] mb-4">Tag Populer</h3>
                <div class="flex flex-wrap gap-2">
                    @php
                        $popularTags = ['#LiterasiDigital', '#BukuBaru', '#Seminar', '#Anak', '#Minangkabau'];
                    @endphp
                    @foreach($popularTags as $tag)
                        <a href="{{ route('berita.public.index', ['search' => $tag]) }}" 
                           class="rounded-lg bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 text-xs font-bold text-emerald-800 transition">
                            {{ $tag }}
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        <!-- Right Content -->
        <main class="min-w-0 space-y-8">
            <!-- Featured Card (Only on Page 1 if there's a featured news) -->
            @if($featured)
                <article class="grid min-w-0 gap-6 overflow-hidden rounded-[2.5rem] border border-slate-100 bg-white p-6 shadow-sm transition duration-300 hover:scale-[1.01] md:grid-cols-[1.2fr_1fr] items-center">
                    <div class="min-w-0 overflow-hidden rounded-3xl h-[280px]">
                        @if($featured->gambar)
                            <img src="{{ Storage::url($featured->gambar) }}" alt="{{ $featured->judul }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full bg-gradient-to-br from-[#04241e] to-[#0a3f35] flex items-center justify-center text-white/20">
                                <i class="fa-regular fa-image text-5xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex min-w-0 flex-col justify-between h-full py-2">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3 text-xs text-slate-500">
                                <span class="bg-[#04241e] text-[#ffdc7c] text-[10px] font-bold px-3 py-1 rounded-md uppercase tracking-wider">
                                    {{ $featured->kategoriBerita?->nama_kategori ?? 'Umum' }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar"></i>
                                    {{ ($featured->tanggal_terbit ?? $featured->created_at)->locale('id')->translatedFormat('d M Y') }}
                                </span>
                            </div>
                            <h2 class="break-words font-serif text-2xl lg:text-3xl font-bold leading-tight text-[#04241e] hover:text-[#ffdc7c] transition">
                                <a href="{{ route('berita.public.show', $featured->slug) }}">{{ $featured->judul }}</a>
                            </h2>
                            <p class="break-words text-slate-500 text-sm leading-relaxed line-clamp-3">
                                {{ Str::limit(strip_tags($featured->isi), 180) }}
                            </p>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('berita.public.show', $featured->slug) }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#04241e] hover:text-[#ffdc7c] transition group">
                                Baca Selengkapnya
                                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition duration-150"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @endif

            <!-- Grid of Smaller Cards -->
            @if($beritaList->isNotEmpty())
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach($beritaList as $item)
                        <article class="flex min-w-0 flex-col justify-between overflow-hidden rounded-[2.5rem] border border-slate-100 bg-white p-5 shadow-sm transition duration-200 hover:shadow-md">
                            <div class="space-y-4">
                                <div class="relative min-w-0 overflow-hidden rounded-3xl h-[200px] bg-slate-50">
                                    @if($item->gambar)
                                        <img src="{{ Storage::url($item->gambar) }}" alt="{{ $item->judul }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full bg-gradient-to-br from-[#04241e] to-[#0a3f35] flex items-center justify-center text-white/20">
                                            <i class="fa-regular fa-image text-4xl"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Tag Badge -->
                                    <span class="absolute top-4 left-4 rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-emerald-500 text-white">
                                        {{ $item->kategoriBerita?->nama_kategori ?? 'Umum' }}
                                    </span>
                                </div>
                                <div class="flex items-center text-xs text-slate-500">
                                    <i class="fa-regular fa-calendar mr-1.5"></i>
                                    {{ ($item->tanggal_terbit ?? $item->created_at)->locale('id')->translatedFormat('d M Y') }}
                                </div>
                                <h3 class="break-words font-serif text-lg font-bold leading-snug text-[#04241e] line-clamp-2 hover:text-[#ffdc7c] transition">
                                    <a href="{{ route('berita.public.show', $item->slug) }}">{{ $item->judul }}</a>
                                </h3>
                                <p class="break-words text-slate-500 text-xs leading-relaxed line-clamp-3">
                                    {{ Str::limit(strip_tags($item->isi), 120) }}
                                </p>
                            </div>
                            <div class="mt-6 pt-4 border-t border-slate-100/60">
                                <a href="{{ route('berita.public.show', $item->slug) }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#04241e] hover:text-[#ffdc7c] transition group">
                                    Baca Selengkapnya
                                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition duration-150"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                @if(!$featured)
                    <div class="bg-white rounded-[2.5rem] p-12 text-center border border-slate-100 shadow-sm">
                        <span class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400 text-3xl mx-auto mb-4">
                            <i class="fa-regular fa-newspaper"></i>
                        </span>
                        <h3 class="font-serif text-lg font-bold text-[#04241e]">Tidak Ada Berita</h3>
                        <p class="text-slate-500 text-sm mt-1">Maaf, tidak ada berita yang cocok dengan kriteria pencarian Anda.</p>
                    </div>
                @endif
            @endif

            <!-- Pagination Section -->
            @if ($beritaPaginated->hasPages())
                <div class="flex items-center justify-center gap-2 pt-8">
                    {{-- Previous Page Link --}}
                    @if ($beritaPaginated->onFirstPage())
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-300 cursor-not-allowed">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </span>
                    @else
                        <a href="{{ $beritaPaginated->previousPageUrl() }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-[#04241e] hover:text-white transition">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </a>
                    @endif

                    {{-- Numbers --}}
                    @php
                        $start = max(1, $beritaPaginated->currentPage() - 2);
                        $end = min($beritaPaginated->lastPage(), $beritaPaginated->currentPage() + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $beritaPaginated->url(1) }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-[#04241e] hover:text-white font-semibold text-sm transition">1</a>
                        @if($start > 2)
                            <span class="flex h-10 w-10 items-center justify-center text-slate-400 font-bold">...</span>
                        @endif
                    @endif

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $beritaPaginated->currentPage())
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#04241e] text-white font-bold text-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $beritaPaginated->url($page) }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-[#04241e] hover:text-white font-semibold text-sm transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endfor

                    @if($end < $beritaPaginated->lastPage())
                        @if($end < $beritaPaginated->lastPage() - 1)
                            <span class="flex h-10 w-10 items-center justify-center text-slate-400 font-bold">...</span>
                        @endif
                        <a href="{{ $beritaPaginated->url($beritaPaginated->lastPage()) }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-[#04241e] hover:text-white font-semibold text-sm transition">{{ $beritaPaginated->lastPage() }}</a>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($beritaPaginated->hasMorePages())
                        <a href="{{ $beritaPaginated->nextPageUrl() }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-[#04241e] hover:text-white transition">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    @else
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-300 cursor-not-allowed">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </span>
                    @endif
                </div>
            @endif
        </main>
    </div>

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
