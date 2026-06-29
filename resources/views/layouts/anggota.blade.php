<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPADI Bukittinggi')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>[x-cloak] { display: none !important; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ===== SIPADI Anggota Dashboard Styles ===== */
        :root {
            --sipadi-green-dark: #0a3d2e;
            --sipadi-green: #0f4c3a;
            --sipadi-green-light: #1a6b50;
            --sipadi-green-accent: #22c55e;
            --sipadi-bg: #f7f7f7;
            --sipadi-card: #ffffff;
            --sipadi-text: #1a1a1a;
            --sipadi-text-muted: #6b7280;
            --sipadi-border: #e5e7eb;
        }

        body {
            background-color: var(--sipadi-bg);
            font-family: 'Figtree', sans-serif;
        }

        /* Navbar */
        .sipadi-navbar {
            background: var(--sipadi-green-dark);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .sipadi-navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            margin-right: 1.5rem;
            white-space: nowrap;
        }

        .sipadi-navbar-brand i {
            font-size: 1.25rem;
        }

        .sipadi-nav-links {
            display: flex;
            align-items: center;
            gap: 0;
            margin-right: auto;
        }

        .sipadi-nav-link {
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .sipadi-nav-link:hover,
        .sipadi-nav-link.active {
            color: white;
            background: rgba(255,255,255,0.12);
        }

        .sipadi-nav-link.active {
            background: rgba(255,255,255,0.18);
            font-weight: 600;
        }

        .sipadi-nav-search {
            position: relative;
            margin-left: 1rem;
        }

        .sipadi-nav-search input {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.15);
            color: white;
            border-radius: 0.5rem;
            padding: 0.45rem 0.75rem 0.45rem 2.25rem;
            font-size: 0.825rem;
            width: 220px;
            outline: none;
            transition: all 0.2s ease;
        }

        .sipadi-nav-search input::placeholder {
            color: rgba(255,255,255,0.45);
        }

        .sipadi-nav-search input:focus {
            background: rgba(255,255,255,0.18);
            border-color: rgba(255,255,255,0.3);
            width: 280px;
        }

        .sipadi-nav-search i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.4);
            font-size: 0.8rem;
        }

        .sipadi-nav-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-left: 1rem;
        }

        .sipadi-btn-kartu {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            padding: 0.4rem 0.9rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .sipadi-btn-kartu:hover {
            background: rgba(255,255,255,0.2);
        }

        .sipadi-nav-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
            border: 2px solid rgba(255,255,255,0.25);
            text-decoration: none;
        }

        .sipadi-nav-avatar:hover {
            background: rgba(255,255,255,0.25);
        }

        /* Footer */
        .sipadi-footer {
            background: var(--sipadi-green-dark);
            color: rgba(255,255,255,0.7);
            padding: 2.5rem 2rem 1.5rem;
            margin-top: 3rem;
        }

        .sipadi-footer-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .sipadi-footer-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sipadi-footer-brand h3 {
            color: white;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .sipadi-footer-brand p {
            font-size: 0.8rem;
            line-height: 1.5;
            max-width: 300px;
        }

        .sipadi-footer-links {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .sipadi-footer-links a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 0.8rem;
            transition: color 0.2s;
        }

        .sipadi-footer-links a:hover {
            color: white;
        }

        .sipadi-footer-bottom {
            text-align: center;
            padding-top: 1rem;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.4);
        }

        /* Mobile responsive navbar */
        .sipadi-mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        @media (max-width: 1024px) {
            .sipadi-nav-links {
                display: none;
            }
            .sipadi-nav-search {
                display: none;
            }
            .sipadi-mobile-toggle {
                display: block;
                margin-left: auto;
                margin-right: 0.5rem;
            }
            .sipadi-navbar {
                padding: 0 1rem;
            }
        }

        @media (max-width: 640px) {
            .sipadi-footer-top {
                flex-direction: column;
            }
            .sipadi-btn-kartu span {
                display: none;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="sipadi-navbar">
        <a href="{{ route('anggota.dashboard') }}" class="sipadi-navbar-brand">
            <img src="{{ asset('images/logo-kota.png') }}" alt="Logo Kota Bukittinggi" style="height: 32px; width: auto; object-fit: contain; margin-right: 4px;">
            <img src="{{ asset('images/logo-dinas.png') }}" alt="Logo Perpustakaan Nasional" style="height: 32px; width: auto; object-fit: contain; margin-right: 8px;">
            SIPADI Bukittinggi
        </a>

        <div class="sipadi-nav-links">
            <a href="{{ route('anggota.dashboard') }}" class="sipadi-nav-link {{ request()->routeIs('anggota.dashboard') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('katalog') }}" class="sipadi-nav-link {{ request()->routeIs('katalog*') ? 'active' : '' }}">Katalog</a>
            
            <!-- Layanan Dropdown -->
            <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                <button @click="open = ! open" class="sipadi-nav-link flex items-center gap-1.5 focus:outline-none">
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
                     class="absolute left-0 z-50 mt-2 w-48 rounded-xl bg-[#0a3d2e] border border-emerald-800 p-2 shadow-xl"
                     style="display: none;">
                     <a href="{{ route('landing') }}#koleksi" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Layanan Perpustakaan</a>
                     <a href="{{ route('landing') }}#kontak" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Fasilitas</a>
                     <a href="{{ route('aduan.create') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Layanan Pengaduan</a>
                     <a href="{{ route('aduan.track') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Lacak Aduan</a>
                </div>
            </div>

            <a href="{{ route('landing') }}#kontak" class="sipadi-nav-link">Fasilitas</a>
            <a href="{{ route('berita.public.index') }}" class="sipadi-nav-link {{ request()->routeIs('berita.public.*') ? 'active' : '' }}">Berita</a>
            <a href="{{ route('agenda.index') }}" class="sipadi-nav-link {{ request()->routeIs('agenda.*') ? 'active' : '' }}">Agenda</a>
        </div>

        <div class="sipadi-nav-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Cari buku, penulis, ISBN...">
        </div>

        <div class="sipadi-nav-actions">
            @if(Route::has('anggota.e-kartu'))
                <a href="{{ route('anggota.e-kartu') }}" class="sipadi-btn-kartu">
                    <i class="fa-regular fa-id-card"></i>
                    <span>Kartu</span>
                </a>
            @endif

            <a href="{{ route('profile.edit') }}" class="sipadi-nav-avatar" title="{{ Auth::user()->nama }}">
                <i class="fa-regular fa-user"></i>
            </a>
        </div>

        <button class="sipadi-mobile-toggle" onclick="document.querySelector('.sipadi-nav-links').classList.toggle('!flex')">
            <i class="fa-solid fa-bars"></i>
        </button>
    </nav>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="sipadi-footer">
        <div class="sipadi-footer-inner">
            <div class="sipadi-footer-top">
                <div class="sipadi-footer-brand">
                    <h3><i class="fa-solid fa-building-columns"></i> SIPADI Bukittinggi</h3>
                    <p>Menghubungkan masyarakat dengan khazanah ilmu pengetahuan dan warisan budaya melalui perpustakaan digital terdepan.</p>
                </div>

                <div class="sipadi-footer-links">
                    <a href="#">Tentang Kami</a>
                    <a href="#">Kebijakan Privasi</a>
                    <a href="#">Syarat & Ketentuan</a>
                    <a href="#">Peta Situs</a>
                    <a href="#">Hubungi Kami</a>
                </div>
            </div>

            <div class="sipadi-footer-bottom">
                &copy; {{ date('Y') }} Dinas Perpustakaan dan Kearsipan Kota Bukittinggi. Seluruh Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
