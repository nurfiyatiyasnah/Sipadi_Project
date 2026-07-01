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
<body class="bg-[#f6f5d8] font-sans text-[#071426]" x-data="{ sidebarOpen: false }">
    <aside class="fixed inset-y-0 left-0 z-40 flex w-[280px] flex-col bg-[#142b3d] text-[#9fb2c7]">
        <div class="flex h-[120px] shrink-0 items-center gap-3 px-8">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#ffdc7c] text-xl text-[#071426]">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div>
                <p class="text-lg font-semibold text-[#ffdc7c]">SIPADI</p>
                <p class="text-sm">Bukittinggi City</p>
            </div>
        </div>

        @php
            $navigationItems = [
                ['Dashboard', 'fa-border-all', 'petugas.dashboard', 'petugas.dashboard'],
                ['Anggota', 'fa-users', null, null],
                ['Buku', 'fa-book-open', 'petugas.koleksi', 'petugas.koleksi'],
                ['Agenda', 'fa-calendar-days', null, null],
                ['Berita', 'fa-newspaper', 'petugas.berita.index', 'petugas.berita.*'],
                ['Aduan', 'fa-message', null, null],
                ['Peminjaman', 'fa-handshake', null, null],
                ['Jadwal', 'fa-calendar-check', null, null],
                ['Organisasi', 'fa-sitemap', 'petugas.organisasi.index', 'petugas.organisasi.*'],
                ['Laporan', 'fa-chart-column', null, null],
            ];
        @endphp

        <nav class="space-y-1">
            @foreach ($navigationItems as [$label, $icon, $routeName, $activePattern])
                @php
                    $active = $activePattern && request()->routeIs($activePattern);
                    $href = $routeName && Route::has($routeName) ? route($routeName) : '#';
                @endphp

                <a href="{{ $href }}" class="flex h-12 items-center gap-4 border-l-4 px-8 text-[17px] {{ $active ? 'border-[#ffdc7c] bg-white/10 text-[#ffdc7c]' : 'border-transparent hover:bg-white/5' }}">
                    <i class="fa-solid {{ $icon }} w-5"></i>
                    <span>{{ $label }}</span>
                </a>
            @endforeach
        </nav>

        <div class="mt-auto px-6 pb-5 pt-6">
            <button class="flex h-12 w-full items-center justify-center gap-3 rounded-lg bg-[#ffdc7c] font-bold text-[#071426]">
                <i class="fa-solid fa-file-arrow-up"></i>
                Upload Document
            </button>
        </div>
    </aside>

    <div class="min-h-screen pl-[280px]">
        <header class="flex h-20 items-center border-b border-slate-200 bg-white px-10">
            <div>
                <h1 class="font-serif text-2xl font-bold leading-tight">Dashboard Admin SIPADI</h1>
                <p class="text-sm text-slate-500">Ringkasan layanan dan aktivitas perpustakaan hari ini</p>
            </div>

            <div class="ml-auto flex items-center gap-5">
                <div class="hidden items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 xl:flex">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#ffdc7c]/80 text-[#071426]">
                        <i class="fa-regular fa-calendar"></i>
                    </span>
                    <div class="leading-tight">
                        <p class="text-xs font-bold tracking-widest text-slate-500">HARI INI</p>
                        <p class="text-sm font-semibold">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>

                <button type="button" class="relative flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white hover:bg-slate-50">
                    <i class="fa-regular fa-bell text-lg"></i>
                    <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500"></span>
                </button>

                <div class="relative" x-data="{ profileOpen: false }" @keydown.escape.window="profileOpen = false">
                    <button type="button" class="flex items-center gap-4 rounded-xl border border-transparent px-2 py-1 hover:border-slate-200 hover:bg-slate-50" @click="profileOpen = ! profileOpen">
                        <span class="text-right leading-tight">
                            <span class="block font-bold">Admin Utama</span>
                            <span class="block text-sm tracking-widest">Librarian I</span>
                        </span>
                        <span class="block h-10 w-10 rounded-full border-2 border-[#ffdc7c] bg-gray-200"></span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-500"></i>
                    </button>

                    <div
                        x-cloak
                        x-show="profileOpen"
                        x-transition.origin.top.right
                        @click.outside="profileOpen = false"
                        class="absolute right-0 top-14 z-50 w-48 overflow-hidden rounded-xl border border-slate-200 bg-white py-2 shadow-lg"
                    >
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            <i class="fa-solid fa-gear w-4"></i>
                            <span>Setting</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm font-semibold text-red-600 hover:bg-red-50">
                                <i class="fa-solid fa-right-from-bracket w-4"></i>
                                <span>Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="px-8 py-10">
            @yield('content')
        </main>
    </div>
</body>
</html>
