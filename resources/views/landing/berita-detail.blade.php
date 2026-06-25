<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $berita->judul }} - SIPADI Bukittinggi</title>

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
                <a href="{{ route('landing') }}" class="text-slate-300 hover:text-white transition">Beranda</a>
                <a href="{{ route('katalog') }}" class="text-slate-300 hover:text-white transition">Katalog</a>
                <a href="{{ route('landing') }}#koleksi" class="text-slate-300 hover:text-white transition">Layanan</a>
                <a href="{{ route('landing') }}#kontak" class="text-slate-300 hover:text-white transition">Fasilitas</a>
                <a href="{{ route('berita.public.index') }}" class="text-[#ffdc7c] border-b-2 border-[#ffdc7c] pb-1">Berita</a>
                <a href="{{ route('agenda.index') }}" class="text-slate-300 hover:text-white transition">Agenda</a>
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

    <!-- Breadcrumb Area -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 pt-10">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('landing') }}" class="hover:text-[#04241e] transition">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <a href="{{ route('berita.public.index') }}" class="hover:text-[#04241e] transition">Berita</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="text-slate-700 truncate max-w-[200px] sm:max-w-xs md:max-w-md">{{ $berita->judul }}</span>
        </nav>
    </div>

    <!-- Main Content Area -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 py-8 grid gap-8 lg:grid-cols-[1fr_320px]">
        <!-- Left Column: News Detail -->
        <main>
            <article class="bg-white rounded-[2.5rem] p-6 lg:p-10 border border-slate-100 shadow-sm space-y-6">
                <!-- Meta Info & Title -->
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
                        <span class="bg-[#04241e] text-[#ffdc7c] text-[10px] font-bold px-3 py-1 rounded-md uppercase tracking-wider">
                            {{ $berita->kategoriBerita?->nama_kategori ?? 'Umum' }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <i class="fa-regular fa-calendar"></i>
                            {{ ($berita->tanggal_terbit ?? $berita->created_at)->locale('id')->translatedFormat('d F Y') }}
                        </span>
                        @if ($berita->petugas)
                            <span class="flex items-center gap-1.5">
                                <i class="fa-regular fa-user"></i>
                                Oleh: {{ $berita->petugas->nama_petugas }}
                            </span>
                        @endif
                    </div>
                    
                    <h1 class="font-serif text-3xl lg:text-4xl font-bold leading-tight text-[#04241e]">
                        {{ $berita->judul }}
                    </h1>
                </div>

                <!-- Featured Image -->
                <div class="overflow-hidden rounded-3xl w-full max-h-[480px]">
                    @if($berita->gambar)
                        <img src="{{ Storage::url($berita->gambar) }}" alt="{{ $berita->judul }}" class="w-full h-full object-cover">
                    @else
                        <div class="h-64 w-full bg-gradient-to-br from-[#04241e] to-[#0a3f35] flex items-center justify-center text-white/20">
                            <i class="fa-regular fa-image text-6xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Body Content -->
                <div class="text-[#061b3a] text-sm lg:text-base leading-relaxed space-y-4 pt-6 border-t border-slate-100/60 font-sans">
                    @if(strip_tags($berita->isi) !== $berita->isi)
                        {!! $berita->isi !!}
                    @else
                        {!! nl2br(e($berita->isi)) !!}
                    @endif
                </div>
            </article>
        </main>

        <!-- Right Column: Sidebar -->
        <aside class="space-y-6">
            <!-- Cari Berita Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <h3 class="font-serif font-bold text-lg text-[#04241e] mb-4">Cari Berita</h3>
                <form action="{{ route('berita.public.index') }}" method="GET" class="relative">
                    <input type="text" name="search" placeholder="Kata kunci..." 
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-4 pr-10 text-sm text-[#061b3a] focus:border-[#ffdc7c] focus:ring-0 focus:outline-none placeholder:text-slate-400">
                    <button type="submit" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#04241e] transition">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>

            <!-- Berita Terbaru Widget -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                <h3 class="font-serif font-bold text-lg text-[#04241e] border-b border-slate-100 pb-2">Berita Terbaru</h3>
                <div class="space-y-4">
                    @forelse($recentBerita as $recent)
                        <a href="{{ route('berita.public.show', $recent->slug) }}" class="flex gap-3 group transition">
                            <div class="w-16 h-16 rounded-xl bg-slate-100 flex-shrink-0 overflow-hidden relative">
                                @if($recent->gambar)
                                    <img src="{{ Storage::url($recent->gambar) }}" alt="{{ $recent->judul }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-200">
                                @else
                                    <div class="h-full w-full bg-gradient-to-br from-[#04241e] to-[#0a3f35] flex items-center justify-center text-white/20">
                                        <i class="fa-regular fa-image text-base"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-col justify-center">
                                <h4 class="font-bold text-xs text-[#061b3a] group-hover:text-[#ffdc7c] transition line-clamp-2">
                                    {{ $recent->judul }}
                                </h4>
                                <span class="text-[10px] text-slate-400 mt-1">
                                    {{ ($recent->tanggal_terbit ?? $recent->created_at)->locale('id')->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-2">Tidak ada berita terbaru lainnya.</p>
                    @endforelse
                </div>
            </div>

            <!-- Kategori Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <h3 class="font-serif font-bold text-lg text-[#04241e] mb-4">Kategori</h3>
                <div class="space-y-3">
                    <a href="{{ route('berita.public.index') }}" 
                       class="flex items-center justify-between text-sm text-slate-600 hover:text-[#04241e] transition">
                        <span>Semua Berita</span>
                        <span class="rounded-full bg-slate-100 text-slate-600 text-xs px-2.5 py-0.5 font-sans font-semibold">
                            {{ $totalBeritaCount }}
                        </span>
                    </a>
                    @foreach($kategoriList as $kat)
                        <a href="{{ route('berita.public.index', ['kategori' => $kat->id_kategori_berita]) }}" 
                           class="flex items-center justify-between text-sm text-slate-600 hover:text-[#04241e] transition">
                            <span>{{ $kat->nama_kategori }}</span>
                            <span class="rounded-full bg-slate-100 text-slate-600 text-xs px-2.5 py-0.5 font-sans font-semibold">
                                {{ $kat->berita_count }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>
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
