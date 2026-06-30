@php
    $isBeranda = request()->routeIs('landing');
    $isKatalog = request()->routeIs('katalog') || request()->routeIs('katalog.show');
    $isLayanan = request()->routeIs('layanan.*');
    $isInformasiActive = request()->routeIs('berita.*') || request()->routeIs('agenda.*');
    $isTentang = request()->routeIs('tentang');
    
    $pengumumanKategoriId = \App\Models\KategoriBerita::where('nama_kategori', 'Pengumuman')->first()?->id_kategori_berita;
@endphp

<header class="bg-[#04241e] text-white">
    <div class="mx-auto max-w-7xl px-6 py-5 lg:px-12 flex items-center justify-between">
        <!-- Logo Section -->
        <a href="{{ route('landing') }}" class="flex items-center gap-3 hover:opacity-90 transition">
            <div class="flex items-center gap-1.5 bg-transparent p-0">
                <img src="{{ asset('images/logo-kota.png') }}" alt="Logo Kota Bukittinggi" class="h-9 w-auto object-contain">
                <img src="{{ asset('images/logo-dinas.png') }}" alt="Logo Perpustakaan Nasional" class="h-9 w-auto object-contain">
            </div>
            <span class="font-serif font-bold text-xl tracking-tight">SIPADI Bukittinggi</span>
        </a>

        <!-- Nav Links -->
        <nav class="hidden md:flex items-center gap-8 text-sm font-semibold">
            <!-- Beranda -->
            <a href="{{ route('landing') }}" class="{{ $isBeranda ? 'text-[#ffdc7c] border-b-2 border-[#ffdc7c] pb-1' : 'text-slate-300 hover:text-white transition' }}">Beranda</a>
            
            <!-- Katalog -->
            <a href="{{ route('katalog') }}" class="{{ $isKatalog ? 'text-[#ffdc7c] border-b-2 border-[#ffdc7c] pb-1' : 'text-slate-300 hover:text-white transition' }}">Katalog</a>
            
            <!-- Informasi Dropdown -->
            <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                <button @click="open = ! open" class="inline-flex items-center gap-1.5 text-sm font-semibold {{ $isInformasiActive ? 'text-[#ffdc7c]' : 'text-slate-300 hover:text-white transition' }} focus:outline-none">
                    <span>Informasi</span>
                    <i class="fa-solid fa-chevron-down text-[10px] transition duration-150" :class="{ 'rotate-180': open }"></i>
                </button>

                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute left-0 z-50 mt-2 w-48 rounded-xl bg-[#04241e] border border-slate-700/60 p-2 shadow-xl"
                     style="display: none;">
                     <a href="{{ route('berita.public.index') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Berita</a>
                     <a href="{{ route('agenda.index') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Agenda</a>
                     @if($pengumumanKategoriId)
                         <a href="{{ route('berita.public.index', ['kategori' => $pengumumanKategoriId]) }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Pengumuman</a>
                     @endif
                </div>
            </div>

            <!-- Layanan Dropdown -->
            <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                <button @click="open = ! open" class="inline-flex items-center gap-1.5 text-sm font-semibold {{ $isLayanan ? 'text-[#ffdc7c]' : 'text-slate-300 hover:text-white transition' }} focus:outline-none">
                    <span>Layanan</span>
                    <i class="fa-solid fa-chevron-down text-[10px] transition duration-150" :class="{ 'rotate-180': open }"></i>
                </button>

                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute left-0 z-50 mt-2 w-48 rounded-xl bg-[#04241e] border border-slate-700/60 p-2 shadow-xl"
                     style="display: none;">
                     <a href="{{ route('layanan.index') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Layanan Perpustakaan</a>
                     <a href="{{ route('landing') }}#kontak" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Fasilitas</a>
                     <a href="{{ route('aduan.create') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Layanan Pengaduan</a>
                     <a href="{{ route('aduan.track') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Lacak Aduan</a>
                </div>
            </div>

            <!-- Tentang Kami -->
            <a href="{{ route('tentang') }}" class="{{ $isTentang ? 'text-[#ffdc7c] border-b-2 border-[#ffdc7c] pb-1' : 'text-slate-300 hover:text-white transition' }}">Tentang Kami</a>
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
