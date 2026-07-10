@php
    $isBeranda = request()->routeIs('landing') || request()->routeIs('anggota.dashboard');
    $isKatalog = request()->routeIs('katalog') || request()->routeIs('katalog.show');
    $isLayanan = request()->routeIs('layanan.*') || request()->routeIs('fasilitas.public.*') || request()->routeIs('aduan.*');
    $isInformasiActive = request()->routeIs('berita.*') || request()->routeIs('agenda.*') || request()->routeIs('pengumuman.public.*');
    $isTentang = request()->routeIs('tentang');

    $desktopLinkClass = 'text-slate-300 hover:text-white transition';
    $desktopActiveClass = 'text-[#ffdc7c] border-b-2 border-[#ffdc7c] pb-1';
    $mobileLinkClass = 'block rounded-xl px-4 py-3 text-sm font-semibold text-slate-200 hover:bg-white/10 hover:text-white transition';
    $mobileActiveClass = 'block rounded-xl bg-white/10 px-4 py-3 text-sm font-bold text-[#ffdc7c] transition';
@endphp

<header class="bg-[#04241e] text-white" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-4 sm:px-6 lg:px-12 lg:py-5">
        <!-- Logo Section -->
        <a href="{{ route('landing') }}" class="flex min-w-0 items-center gap-2.5 hover:opacity-90 transition sm:gap-3">
            <span class="flex shrink-0 items-center gap-1.5 bg-transparent p-0">
                <img src="{{ asset('images/logo-kota.png') }}" alt="Logo Kota Bukittinggi" class="h-8 w-auto shrink-0 object-contain sm:h-9">
                <img src="{{ asset('images/logo-dinas.png') }}" alt="Logo Perpustakaan Nasional" class="h-8 w-auto shrink-0 object-contain sm:h-9">
            </span>
            <span class="min-w-0 truncate font-serif text-base font-bold tracking-tight sm:text-xl">SIPADI Bukittinggi</span>
        </a>

        <!-- Nav Links -->
        <nav class="hidden items-center gap-8 text-sm font-semibold lg:flex">
            <!-- Beranda -->
            <a href="{{ Auth::check() && ! Auth::user()->isPetugas() ? route('anggota.dashboard') : route('landing') }}" class="{{ $isBeranda ? $desktopActiveClass : $desktopLinkClass }}">Beranda</a>

            <!-- Katalog -->
            <a href="{{ route('katalog') }}" class="{{ $isKatalog ? $desktopActiveClass : $desktopLinkClass }}">Katalog</a>

            <!-- Informasi Dropdown -->
            <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                <button @click="open = ! open" type="button" class="inline-flex items-center gap-1.5 text-sm font-semibold {{ $isInformasiActive ? 'text-[#ffdc7c]' : 'text-slate-300 hover:text-white transition' }} focus:outline-none">
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
                     <a href="{{ route('pengumuman.public.index') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Pengumuman</a>
                </div>
            </div>

            <!-- Layanan Dropdown -->
            <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                <button @click="open = ! open" type="button" class="inline-flex items-center gap-1.5 text-sm font-semibold {{ $isLayanan ? 'text-[#ffdc7c]' : 'text-slate-300 hover:text-white transition' }} focus:outline-none">
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
                     <a href="{{ route('fasilitas.public.index') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Fasilitas</a>
                     <a href="{{ route('aduan.create') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Layanan Pengaduan</a>
                     <a href="{{ route('aduan.track') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Lacak Aduan</a>
                </div>
            </div>

            <!-- Tentang Kami -->
            <a href="{{ route('tentang') }}" class="{{ $isTentang ? $desktopActiveClass : $desktopLinkClass }}">Tentang Kami</a>
        </nav>

        <div class="flex min-w-0 items-center gap-2 sm:gap-4">
            <!-- Action Buttons -->
            <div class="hidden items-center gap-2 sm:flex sm:gap-3">
                @auth
                    @if (Auth::user()->isPetugas())
                        <a href="{{ route('petugas.dashboard') }}" class="rounded-xl bg-[#ffdc7c] px-4 py-2.5 text-sm font-bold text-[#04241e] hover:bg-[#ffe399] transition shadow-md shadow-[#ffdc7c]/10 lg:px-5">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('anggota.e-kartu') }}" class="rounded-xl bg-[#ffdc7c] px-4 py-2.5 text-sm font-bold text-[#04241e] hover:bg-[#ffe399] transition shadow-md shadow-[#ffdc7c]/10 lg:px-5">
                            E-Kartu
                        </a>
                    @endif

                    <!-- Vertical Divider (Left of Bell) -->
                    @if (! Auth::user()->isPetugas())
                        <div class="h-8 w-px bg-slate-700/80 mx-1"></div>

                        <!-- Notification Bell Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                            <button @click="open = ! open" type="button" class="relative text-slate-300 hover:text-white transition focus:outline-none cursor-pointer flex items-center justify-center h-9 w-9 rounded-full hover:bg-white/10">
                                <i class="fa-regular fa-bell text-lg"></i>
                                @if (isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                    <span class="absolute top-1.5 right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-extrabold text-white ring-2 ring-[#04241e]">
                                        {{ $unreadNotificationsCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 z-50 mt-3 w-80 rounded-2xl bg-[#09221d] border border-slate-700/60 p-2 shadow-xl"
                                 style="display: none;">

                                 <div class="px-4 py-2.5 border-b border-slate-800 flex justify-between items-center">
                                     <span class="text-xs font-bold text-white uppercase tracking-wider">Notifikasi</span>
                                     @if (isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                         <span class="bg-[#ffdc7c]/10 text-[#ffdc7c] px-2 py-0.5 rounded-full text-[10px] font-bold">
                                             {{ $unreadNotificationsCount }} Baru
                                         </span>
                                     @endif
                                 </div>

                                 <div class="max-h-80 overflow-y-auto py-1">
                                     @if (isset($latestNotifications) && $latestNotifications->isNotEmpty())
                                         @foreach ($latestNotifications as $notif)
                                             @php
                                                 $isUnread = in_array($notif->status_baca, ['belum_dibaca', 'Belum Dibaca']);
                                             @endphp
                                             <a href="{{ route('anggota.notifikasi.read', $notif->id_notifikasi) }}" class="block px-4 py-3 hover:bg-white/5 rounded-xl transition text-left relative">
                                                 <div class="flex justify-between items-start gap-2">
                                                     <p class="text-sm font-bold {{ $isUnread ? 'text-[#ffdc7c]' : 'text-slate-200' }}">{{ $notif->judul }}</p>
                                                     @if ($isUnread)
                                                         <span class="h-2 w-2 rounded-full bg-red-500 mt-1 flex-shrink-0" title="Belum dibaca"></span>
                                                     @endif
                                                 </div>
                                                 <p class="text-xs text-slate-400 mt-1 line-clamp-2 leading-relaxed">
                                                     {{ $notif->isi }}
                                                 </p>
                                                 <span class="block text-[10px] text-slate-500 mt-1.5 font-medium">
                                                     {{ $notif->dikirim_pada ? $notif->dikirim_pada->diffForHumans() : $notif->created_at->diffForHumans() }}
                                                 </span>
                                             </a>
                                         @endforeach
                                     @else
                                         <div class="py-8 text-center">
                                             <span class="block text-2xl text-slate-600 mb-2">
                                                 <i class="fa-regular fa-bell-slash"></i>
                                             </span>
                                             <p class="text-xs text-slate-500">Tidak ada notifikasi baru</p>
                                         </div>
                                     @endif
                                 </div>

                                 <div class="border-t border-slate-800 p-1.5">
                                     <a href="{{ route('anggota.notifikasi.index') }}" class="block text-center rounded-xl bg-white/5 hover:bg-white/10 py-2.5 text-xs font-bold text-white transition">
                                         Lihat Semua Notifikasi
                                     </a>
                                 </div>
                            </div>
                        </div>
                    @endif

                    <!-- Vertical Divider (Right of Bell) -->
                    <div class="h-8 w-px bg-slate-700/80 mx-1"></div>

                    <!-- Profile Dropdown -->
                    <div class="relative min-w-0" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                        <button @click="open = ! open" type="button" class="flex min-w-0 items-center gap-3 focus:outline-none text-left cursor-pointer group">
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
                            <div class="hidden max-w-48 min-w-0 leading-tight xl:block">
                                <span class="block truncate text-sm font-bold text-white group-hover:text-slate-200 transition-colors">{{ Auth::user()->nama }}</span>
                                <span class="block truncate text-[11px] text-slate-400 mt-0.5">{{ Auth::user()->role?->nama_role ?? 'Anggota' }}</span>
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
                                 <a href="{{ route('anggota.peminjaman-saya') }}" class="block rounded-lg px-4 py-2.5 text-sm text-white hover:bg-white/10 transition">
                                     Peminjaman Saya
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
                    <a href="{{ route('login') }}" class="rounded-xl border border-slate-500 px-4 py-2.5 text-sm font-semibold hover:bg-white/10 transition lg:px-5">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-[#ffdc7c] px-4 py-2.5 text-sm font-bold text-[#04241e] hover:bg-[#ffe399] transition shadow-md shadow-[#ffdc7c]/10 lg:px-5">
                        Daftar
                    </a>
                @endauth
            </div>

            <button type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/15 bg-white/5 text-white transition hover:bg-white/10 lg:hidden"
                    @click="mobileOpen = ! mobileOpen"
                    :aria-expanded="mobileOpen.toString()"
                    aria-controls="public-mobile-menu"
                    aria-label="Toggle navigation">
                <i class="fa-solid" :class="mobileOpen ? 'fa-xmark' : 'fa-bars'"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div id="public-mobile-menu"
         x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="border-t border-white/10 bg-[#04241e] px-4 pb-5 pt-3 shadow-xl lg:hidden"
         style="display: none;">
        <nav class="mx-auto max-w-7xl space-y-1">
            <a href="{{ Auth::check() && ! Auth::user()->isPetugas() ? route('anggota.dashboard') : route('landing') }}" @click="mobileOpen = false" class="{{ $isBeranda ? $mobileActiveClass : $mobileLinkClass }}">Beranda</a>
            <a href="{{ route('katalog') }}" @click="mobileOpen = false" class="{{ $isKatalog ? $mobileActiveClass : $mobileLinkClass }}">Katalog</a>

            <div class="px-4 pb-1 pt-4 text-[11px] font-bold uppercase tracking-widest text-slate-500">Informasi</div>
            <a href="{{ route('berita.public.index') }}" @click="mobileOpen = false" class="{{ request()->routeIs('berita.*') ? $mobileActiveClass : $mobileLinkClass }}">Berita</a>
            <a href="{{ route('agenda.index') }}" @click="mobileOpen = false" class="{{ request()->routeIs('agenda.*') ? $mobileActiveClass : $mobileLinkClass }}">Agenda</a>
            <a href="{{ route('pengumuman.public.index') }}" @click="mobileOpen = false" class="{{ request()->routeIs('pengumuman.public.*') ? $mobileActiveClass : $mobileLinkClass }}">Pengumuman</a>

            <div class="px-4 pb-1 pt-4 text-[11px] font-bold uppercase tracking-widest text-slate-500">Layanan</div>
            <a href="{{ route('layanan.index') }}" @click="mobileOpen = false" class="{{ request()->routeIs('layanan.*') ? $mobileActiveClass : $mobileLinkClass }}">Layanan Perpustakaan</a>
            <a href="{{ route('fasilitas.public.index') }}" @click="mobileOpen = false" class="{{ request()->routeIs('fasilitas.public.*') ? $mobileActiveClass : $mobileLinkClass }}">Fasilitas</a>
            <a href="{{ route('aduan.create') }}" @click="mobileOpen = false" class="{{ request()->routeIs('aduan.create') ? $mobileActiveClass : $mobileLinkClass }}">Layanan Pengaduan</a>
            <a href="{{ route('aduan.track') }}" @click="mobileOpen = false" class="{{ request()->routeIs('aduan.track') ? $mobileActiveClass : $mobileLinkClass }}">Lacak Aduan</a>

            <a href="{{ route('tentang') }}" @click="mobileOpen = false" class="{{ $isTentang ? $mobileActiveClass : $mobileLinkClass }}">Tentang Kami</a>

            <div class="mt-4 border-t border-white/10 pt-4">
                @auth
                    @if (Auth::user()->isPetugas())
                        <a href="{{ route('petugas.dashboard') }}" @click="mobileOpen = false" class="block rounded-xl bg-[#ffdc7c] px-4 py-3 text-center text-sm font-bold text-[#04241e] transition hover:bg-[#ffe399]">Dashboard</a>
                    @else
                        <div class="grid gap-2 sm:grid-cols-2">
                            <a href="{{ route('anggota.e-kartu') }}" @click="mobileOpen = false" class="block rounded-xl bg-[#ffdc7c] px-4 py-3 text-center text-sm font-bold text-[#04241e] transition hover:bg-[#ffe399]">E-Kartu</a>
                            <a href="{{ route('anggota.peminjaman-saya') }}" @click="mobileOpen = false" class="block rounded-xl border border-white/15 px-4 py-3 text-center text-sm font-bold text-white transition hover:bg-white/10">Peminjaman Saya</a>
                            <a href="{{ route('anggota.notifikasi.index') }}" @click="mobileOpen = false" class="block rounded-xl border border-white/15 px-4 py-3 text-center text-sm font-bold text-white transition hover:bg-white/10 sm:col-span-2">Notifikasi</a>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full rounded-xl border border-white/15 px-4 py-3 text-center text-sm font-bold text-white transition hover:bg-white/10">
                            Keluar
                        </button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" @click="mobileOpen = false" class="rounded-xl border border-white/15 px-4 py-3 text-center text-sm font-bold text-white transition hover:bg-white/10">Masuk</a>
                        <a href="{{ route('register') }}" @click="mobileOpen = false" class="rounded-xl bg-[#ffdc7c] px-4 py-3 text-center text-sm font-bold text-[#04241e] transition hover:bg-[#ffe399]">Daftar</a>
                    </div>
                @endauth
            </div>
        </nav>
    </div>
</header>
