@php
    $isBeranda = request()->routeIs('landing');
    $isKatalog = request()->routeIs('katalog') || request()->routeIs('katalog.show');
    $isInformasiActive = request()->routeIs('berita.*') || request()->routeIs('agenda.*');
    
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
                <button @click="open = ! open" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-300 hover:text-white transition focus:outline-none">
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
                     <a href="{{ route('landing') }}#koleksi" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Layanan Perpustakaan</a>
                     <a href="{{ route('landing') }}#kontak" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Fasilitas</a>
                     <a href="{{ route('aduan.create') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Layanan Pengaduan</a>
                     <a href="{{ route('aduan.track') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Lacak Aduan</a>
                </div>
            </div>

            <!-- Tentang Kami -->
            <a href="{{ route('landing') }}#kontak" class="text-slate-300 hover:text-white transition">Tentang Kami</a>
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

                <!-- Vertical Divider (Left of Bell) -->
                <div class="h-8 w-px bg-slate-700/80 mx-1"></div>

                <!-- Notification Bell -->
                <button type="button" class="relative text-slate-300 hover:text-white transition focus:outline-none">
                    <i class="fa-regular fa-bell text-lg"></i>
                </button>

                <!-- Vertical Divider (Right of Bell) -->
                <div class="h-8 w-px bg-slate-700/80 mx-1"></div>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                    <button @click="open = ! open" class="flex items-center gap-3 focus:outline-none text-left cursor-pointer group">
                        <!-- Circular Avatar -->
                        <div class="h-10 w-10 rounded-full overflow-hidden border-2 border-slate-600 group-hover:border-[#ffdc7c] transition-colors duration-150 flex items-center justify-center">
                            @if (Auth::user()->anggota && Auth::user()->anggota->foto)
                                <img src="{{ asset('storage/' . Auth::user()->anggota->foto) }}" alt="Avatar" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full bg-[#ffdc7c] text-[#04241e] flex items-center justify-center font-bold text-sm">
                                    {{ mb_substr(Auth::user()->nama, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <!-- User Name and Role -->
                        <div class="hidden sm:block leading-tight">
                            <span class="block text-sm font-bold text-white group-hover:text-slate-200 transition-colors">{{ Auth::user()->nama }}</span>
                            <span class="block text-[11px] text-slate-400 mt-0.5">{{ Auth::user()->role?->nama_role ?? 'Anggota' }}</span>
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 z-50 mt-3 w-52 rounded-xl bg-[#09221d] border border-slate-700/60 p-2 shadow-xl"
                         style="display: none;">
                         
                         @if (Auth::user()->isPetugas())
                             <a href="{{ route('petugas.dashboard') }}" class="block rounded-lg px-4 py-2.5 text-sm text-white hover:bg-white/10 transition">
                                 Dashboard Admin
                             </a>
                         @else
                             <a href="#" class="block rounded-lg px-4 py-2.5 text-sm text-white hover:bg-white/10 transition">
                                 Riwayat Peminjaman
                             </a>
                         @endif
                         
                         <form method="POST" action="{{ route('logout') }}" class="block w-full">
                             @csrf
                             <button type="submit" class="block w-full text-left rounded-lg px-4 py-2.5 text-sm text-white hover:bg-white/10 transition">
                                 Keluar
                             </button>
                         </form>
                    </div>
                </div>
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
