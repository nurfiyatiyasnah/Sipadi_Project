<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPADI Bukittinggi - Perpustakaan & Arsip Digital')</title>
 
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>[x-cloak] { display: none !important; }</style>
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
                <a href="{{ route('landing') }}" class="{{ request()->routeIs('landing') ? 'text-[#ffdc7c] border-b-2 border-[#ffdc7c] pb-1' : 'text-slate-300 hover:text-white transition' }}">Beranda</a>
                <a href="/#koleksi" class="text-slate-300 hover:text-white transition">Katalog</a>
                <a href="/#koleksi" class="text-slate-300 hover:text-white transition">Layanan</a>
                <a href="/#kontak" class="text-slate-300 hover:text-white transition">Fasilitas</a>
                <a href="/#berita" class="text-slate-300 hover:text-white transition">Berita</a>
                <a href="{{ route('agenda.index') }}" class="{{ request()->routeIs('agenda.*') ? 'text-[#ffdc7c] border-b-2 border-[#ffdc7c] pb-1' : 'text-slate-300 hover:text-white transition' }}">Agenda</a>
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
 
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
 
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
