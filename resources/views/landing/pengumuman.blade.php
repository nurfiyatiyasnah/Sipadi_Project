<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman - SIPADI Bukittinggi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f5e9] font-sans text-[#061b3a] antialiased">

    <!-- Header / Navbar -->
    @include('layouts.public_navbar')

    <!-- Header / Title Section -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 py-10">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-emerald-800">Pusat Informasi</p>
                <h1 class="font-serif text-4xl lg:text-5xl font-bold text-[#04241e] mt-2">Pengumuman</h1>
                <p class="text-slate-500 mt-3 text-sm lg:text-base max-w-2xl">
                    Pembaruan terkini, informasi operasional, dan berita kegiatan dari Dinas Perpustakaan dan Kearsipan Kota Bukittinggi.
                </p>
            </div>
            
            <!-- Search Bar -->
            <div class="w-full md:w-80">
                <form action="{{ route('pengumuman.public.index') }}" method="GET" class="relative">
                    @if(request('target'))
                        <input type="hidden" name="target" value="{{ request('target') }}">
                    @endif
                    @if(request('bulan'))
                        <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pengumuman..." 
                           class="w-full rounded-2xl border border-slate-200 bg-white py-3.5 pl-5 pr-12 text-sm text-[#061b3a] focus:border-[#ffdc7c] focus:ring-0 focus:outline-none placeholder:text-slate-400 shadow-sm">
                    <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#04241e] transition">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 pb-16 grid gap-8 lg:grid-cols-[300px_1fr]">
        
        <!-- Left Sidebar -->
        <aside class="space-y-6">
            
            <!-- Kategori / Target Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <h3 class="font-serif font-bold text-lg text-[#04241e] mb-4">Kategori</h3>
                <div class="space-y-3">
                    @foreach($targets as $targetItem)
                        <a href="{{ route('pengumuman.public.index', array_merge(request()->except('page'), ['target' => $targetItem['value']])) }}" 
                           class="flex items-center justify-between text-sm {{ (request('target', 'Semua') === $targetItem['value']) ? 'font-bold text-[#04241e]' : 'text-slate-600 hover:text-[#04241e]' }} transition">
                            <span>{{ $targetItem['name'] }}</span>
                            <span class="rounded-full {{ (request('target', 'Semua') === $targetItem['value']) ? 'bg-[#04241e] text-white' : 'bg-slate-100 text-slate-600' }} text-xs px-2.5 py-0.5 font-sans font-semibold">
                                {{ $targetItem['count'] }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Arsip Bulan Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <h3 class="font-serif font-bold text-lg text-[#04241e] mb-4">Arsip Bulan</h3>
                <form action="{{ route('pengumuman.public.index') }}" method="GET">
                    @if(request('target'))
                        <input type="hidden" name="target" value="{{ request('target') }}">
                    @endif
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <div class="relative">
                        <select name="bulan" onchange="this.form.submit()" 
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 px-4 text-sm text-[#061b3a] focus:border-[#ffdc7c] focus:ring-0 focus:outline-none appearance-none cursor-pointer">
                            <option value="">Pilih Bulan...</option>
                            @foreach($monthsList as $month)
                                <option value="{{ $month['value'] }}" @selected(request('bulan') === $month['value'])>
                                    {{ $month['label'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Right Content / Announcements List -->
        <main class="space-y-6">
            
            <!-- Featured Card (Penting Highlight) -->
            @if($featured)
                <article class="bg-white rounded-3xl p-6 border-2 border-[#ffdc7c] shadow-md transition duration-300 hover:scale-[1.005] flex flex-col md:flex-row gap-6 relative overflow-hidden">
                    <div class="w-full md:w-1/3 h-48 md:h-auto min-h-[180px] rounded-2xl overflow-hidden flex-shrink-0 bg-slate-50">
                        @if($featured->gambar)
                            <img src="{{ Storage::url($featured->gambar) }}" alt="{{ $featured->judul }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/default-pengumuman.png') }}" alt="{{ $featured->judul }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1 flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3 text-xs text-slate-500">
                                <span class="bg-[#ffdc7c] text-[#04241e] text-[10px] font-extrabold px-3 py-1 rounded-md uppercase tracking-wider">
                                    {{ $featured->prioritas }}
                                </span>
                                <span class="flex items-center gap-1.5 font-semibold text-slate-400">
                                    <i class="fa-regular fa-calendar"></i>
                                    {{ $featured->tanggal_mulai->locale('id')->translatedFormat('d F Y') }}
                                </span>
                            </div>
                            <h2 class="font-serif text-2xl lg:text-3xl font-bold leading-tight text-[#04241e] hover:text-emerald-800 transition">
                                <a href="{{ route('pengumuman.public.show', $featured->slug) }}">{{ $featured->judul }}</a>
                            </h2>
                            <p class="text-slate-600 text-sm leading-relaxed line-clamp-3">
                                {{ Str::limit(strip_tags($featured->isi), 220) }}
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('pengumuman.public.show', $featured->slug) }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#04241e] hover:text-[#ffdc7c] transition group">
                                Baca Selengkapnya
                                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition duration-150"></i>
                            </a>
                            <span class="rounded-lg bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-800">
                                {{ $featured->target_pengguna }}
                            </span>
                        </div>
                    </div>
                </article>
            @endif

            <!-- Grid/List of Standard Cards -->
            @if($items->isNotEmpty())
                <div class="space-y-4">
                    @foreach($items as $item)
                        <article class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col md:flex-row gap-5 items-start">
                            
                            <!-- Left: Date Box -->
                            <div class="flex flex-col items-center justify-center bg-sky-50 text-sky-800 rounded-2xl w-24 h-24 p-3 flex-shrink-0 text-center border border-sky-100/50">
                                <span class="text-3xl font-extrabold font-serif leading-none">
                                    {{ $item->tanggal_mulai->format('d') }}
                                </span>
                                <span class="text-[10px] font-bold uppercase tracking-wider mt-1.5 whitespace-nowrap">
                                    {{ $item->tanggal_mulai->locale('id')->translatedFormat('M Y') }}
                                </span>
                            </div>

                            <!-- Right: Content -->
                            <div class="flex-1 flex flex-col justify-between self-stretch">
                                <div class="space-y-2">
                                    <h3 class="font-serif text-xl font-bold leading-snug text-[#04241e] hover:text-emerald-800 transition line-clamp-1">
                                        <a href="{{ route('pengumuman.public.show', $item->slug) }}">{{ $item->judul }}</a>
                                    </h3>
                                    <p class="text-slate-500 text-sm leading-relaxed line-clamp-2">
                                        {{ Str::limit(strip_tags($item->isi), 180) }}
                                    </p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-100/60 flex items-center gap-2 text-xs">
                                    <a href="{{ route('pengumuman.public.show', $item->slug) }}" class="font-bold text-[#04241e] hover:text-emerald-800 transition">
                                        Detail
                                    </a>
                                    <span class="text-slate-300">•</span>
                                    <span class="font-semibold text-slate-400">
                                        Target: {{ $item->target_pengguna }}
                                    </span>
                                    @if($item->prioritas === 'Penting')
                                        <span class="text-slate-300">•</span>
                                        <span class="font-bold text-amber-600">
                                            Highlight
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Far Right: Image Thumbnail -->
                            <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl overflow-hidden flex-shrink-0 self-center border border-slate-100">
                                @if($item->gambar)
                                    <img src="{{ Storage::url($item->gambar) }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('images/default-pengumuman.png') }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                @if(!$featured)
                    <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm">
                        <span class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400 text-3xl mx-auto mb-4">
                            <i class="fa-regular fa-bell-slash"></i>
                        </span>
                        <h3 class="font-serif text-lg font-bold text-[#04241e]">Tidak Ada Pengumuman</h3>
                        <p class="text-slate-500 text-sm mt-1">Maaf, tidak ada pengumuman aktif saat ini.</p>
                    </div>
                @endif
            @endif

            <!-- Pagination -->
            @if ($announcementsPaginated->hasPages())
                <div class="flex items-center justify-center gap-2 pt-6">
                    {{-- Previous Page Link --}}
                    @if ($announcementsPaginated->onFirstPage())
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-300 cursor-not-allowed">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </span>
                    @else
                        <a href="{{ $announcementsPaginated->previousPageUrl() }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-[#04241e] hover:text-white transition">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </a>
                    @endif

                    {{-- Numbers --}}
                    @php
                        $start = max(1, $announcementsPaginated->currentPage() - 2);
                        $end = min($announcementsPaginated->lastPage(), $announcementsPaginated->currentPage() + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $announcementsPaginated->url(1) }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-[#04241e] hover:text-white font-semibold text-sm transition">1</a>
                        @if($start > 2)
                            <span class="flex h-10 w-10 items-center justify-center text-slate-400 font-bold">...</span>
                        @endif
                    @endif

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $announcementsPaginated->currentPage())
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#04241e] text-white font-bold text-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $announcementsPaginated->url($page) }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-[#04241e] hover:text-white font-semibold text-sm transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endfor

                    @if($end < $announcementsPaginated->lastPage())
                        @if($end < $announcementsPaginated->lastPage() - 1)
                            <span class="flex h-10 w-10 items-center justify-center text-slate-400 font-bold">...</span>
                        @endif
                        <a href="{{ $announcementsPaginated->url($announcementsPaginated->lastPage()) }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-[#04241e] hover:text-white font-semibold text-sm transition">{{ $announcementsPaginated->lastPage() }}</a>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($announcementsPaginated->hasMorePages())
                        <a href="{{ $announcementsPaginated->nextPageUrl() }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-[#04241e] hover:text-white transition">
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
