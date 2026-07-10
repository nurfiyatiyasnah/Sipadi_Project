<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIPADI </title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>[x-cloak] { display: none !important; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#fcf3d7] font-sans text-[#071426]" x-data="{ sidebarOpen: false }">
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-40 flex w-[280px] flex-col bg-[#0e1f30] text-[#869ab8]">
        <!-- Brand Header -->
        <div class="flex h-24 shrink-0 flex-col justify-center px-8 border-b border-slate-800/40">
            <h1 class="text-xl font-bold tracking-tight text-white">SIPADI Admin</h1>
            <p class="text-xs text-[#869ab8]/80 mt-0.5">Library System</p>
        </div>

        @php
            $navigationItems = [
                ['Dashboard', 'fa-solid fa-table-cells-large', 'petugas.dashboard', 'petugas.dashboard'],
                ['Anggota', 'fa-solid fa-user-group', 'petugas.anggota.index', 'petugas.anggota.*'],
                ['Buku', 'fa-solid fa-book', 'petugas.koleksi', ['petugas.koleksi', 'petugas.buku.*']],
                ['Peminjaman', 'fa-solid fa-right-left', 'petugas.peminjaman.index', 'petugas.peminjaman.*'],
                ['Pengembalian', 'fa-solid fa-right-left', 'petugas.pengembalian.index', 'petugas.pengembalian.*'],
                ['Agenda', 'fa-regular fa-calendar', 'petugas.agenda.index', 'petugas.agenda.*'],
                ['Berita', 'fa-regular fa-newspaper', 'petugas.berita.index', 'petugas.berita.*'],
                ['Pengumuman', 'fa-solid fa-bullhorn', 'petugas.pengumuman.index', 'petugas.pengumuman.*'],
                ['Aduan', 'fa-solid fa-triangle-exclamation', 'petugas.aduan.index', 'petugas.aduan.*'],
                ['divider', '', null, null],
                ['Profil Perpustakaan', 'fa-solid fa-building-columns', null, null],
                ['Struktur Organisasi', 'fa-solid fa-sitemap', 'petugas.organisasi.index', 'petugas.organisasi.*'],
                ['Prestasi', 'fa-solid fa-trophy', null, null],
                ['Layanan', 'fa-solid fa-handshake-angle', 'petugas.layanan.index', 'petugas.layanan.*'],
                ['Fasilitas', 'fa-solid fa-couch', 'petugas.fasilitas.index', 'petugas.fasilitas.*'],
                ['Laporan', 'fa-solid fa-chart-column', null, null],
            ];
        @endphp

        <!-- Navigation Menu -->
        <nav class="sipadi-sidebar-scrollbar flex-1 space-y-1 overflow-y-auto px-4 py-6">
            @foreach ($navigationItems as [$label, $icon, $routeName, $activePattern])
                @if ($label === 'divider')
                    <hr class="my-4 border-slate-800/60">
                @else
                    @php
                        $active = $activePattern && request()->routeIs($activePattern);
                        $href = $routeName && Route::has($routeName) ? route($routeName) : '#';
                    @endphp
                    <a href="{{ $href }}" class="flex h-11 items-center gap-3.5 rounded-xl px-4 text-[15px] transition {{ $active ? 'bg-[#1b2e46] text-[#ffd56b] font-medium shadow-sm' : 'text-[#869ab8] hover:bg-white/5 hover:text-white' }}">
                        <i class="{{ $icon }} w-5 text-center text-lg {{ $active ? 'text-[#ffd56b]' : '' }}"></i>
                        <span>{{ $label }}</span>
                    </a>
                @endif
            @endforeach
        </nav>

        <!-- Sidebar Footer -->
        <div class="mt-auto border-t border-slate-800/40 p-4 space-y-1">
            <a href="{{ route('profile.edit') }}" class="flex h-11 items-center gap-3.5 rounded-xl px-4 text-[15px] text-[#869ab8] transition hover:bg-white/5 hover:text-white">
                <i class="fa-regular fa-circle-user w-5 text-center text-lg"></i>
                <span>Admin Profile</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" id="logout-form-sidebar">
                @csrf
                <button type="submit" class="flex h-11 w-full items-center gap-3.5 rounded-xl px-4 text-left text-[15px] text-[#869ab8] transition hover:bg-white/5 hover:text-white">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center text-lg"></i>
                    <span>Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="min-h-screen pl-[280px]">
        <!-- Topbar -->
        <header class="flex h-20 items-center justify-between bg-white px-10 shadow-sm">
            <!-- Left empty block for balance -->
            <div class="w-1/4"></div>

            <!-- Centered Search Input -->
            <div class="relative w-[450px]">
                <span class="absolute inset-y-0 left-4 flex items-center text-slate-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" placeholder="Search books, members..." class="w-full rounded-full border border-slate-200 bg-slate-50 py-2.5 pl-11 pr-4 text-sm outline-none transition focus:border-slate-300 focus:bg-white">
            </div>

            <!-- Right Profile Section -->
            <div class="flex w-1/4 items-center justify-end gap-6">
                <!-- Notifications -->
                <button type="button" class="relative text-slate-500 hover:text-slate-800 transition">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0.5 h-2 w-2 rounded-full bg-red-500"></span>
                </button>

                <!-- Settings -->
                <a href="{{ route('profile.edit') }}" class="text-slate-500 hover:text-slate-800 transition">
                    <i class="fa-solid fa-gear text-xl"></i>
                    <span class="sr-only">Setting</span>
                </a>

                <!-- Separator -->
                <span class="h-6 w-px bg-slate-200"></span>

                <!-- User Widget -->
                <div class="flex items-center gap-3">
                    <div class="text-right leading-none">
                        <span class="block text-sm font-semibold text-slate-800">Librarian Admin</span>
                        <span class="block text-xs text-slate-400 mt-1">Administrator</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?auto=format&fit=crop&q=80&w=80" alt="Avatar" class="h-10 w-10 rounded-full border border-slate-200 object-cover shadow-sm">
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-8">
            @yield('content')
        </main>
    </div>
</body>
</html>
