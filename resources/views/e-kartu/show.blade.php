<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>E-Kartu Anggota - SIPADI Bukittinggi</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            @page {
                size: landscape;
                margin: 10mm;
            }
            body {
                background: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .print-card-wrapper {
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                min-height: 100vh !important;
                background: transparent !important;
            }
            .e-card-element {
                box-shadow: none !important;
                border: 1px solid #e2e8f0 !important;
                background-color: #061b3a !important; /* Keep original background color in print */
            }
        }
    </style>
</head>
<body class="min-h-screen bg-[#f6f5e9] font-sans text-[#061b3a] antialiased">

    <!-- Header / Navbar -->
    <header class="bg-[#04241e] text-white no-print" x-data="{ mobileMenuOpen: false }">
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
                <a href="{{ route('landing') }}" class="text-slate-300 hover:text-white transition">Beranda</a>
                <a href="{{ route('katalog') }}" class="text-slate-300 hover:text-white transition">Katalog</a>
                
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
                         class="absolute left-0 z-50 mt-2 w-48 rounded-xl bg-[#04241e] border border-slate-700/60 p-2 shadow-xl text-left"
                         style="display: none;">
                         <a href="{{ route('landing') }}#koleksi" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Layanan Perpustakaan</a>
                         <a href="{{ route('landing') }}#kontak" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Fasilitas</a>
                         <a href="{{ route('aduan.create') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Layanan Pengaduan</a>
                         <a href="{{ route('aduan.track') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">Lacak Aduan</a>
                    </div>
                </div>

                <a href="{{ route('landing') }}#kontak" class="text-slate-300 hover:text-white transition">Fasilitas</a>
                <a href="{{ route('anggota.e-kartu') }}" class="text-[#ffdc7c] border-b-2 border-[#ffdc7c] pb-1">Profil</a>
            </nav>

            <!-- Right Actions -->
            <div class="flex items-center gap-6">
                <!-- Notifications icon -->
                <div class="relative text-slate-300 hover:text-white transition cursor-pointer">
                    <i class="fa-regular fa-bell text-lg"></i>
                </div>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                    <button @click="open = ! open" class="flex items-center focus:outline-none" title="Profil Saya">
                        @if ($anggota->foto)
                            <img src="{{ asset('storage/'.$anggota->foto) }}" alt="Foto {{ $anggota->nama_lengkap }}" class="h-9 w-9 rounded-full object-cover ring-2 ring-[#ffdc7c]/50">
                        @else
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#ffdc7c]/20 text-[#ffdc7c] ring-2 ring-[#ffdc7c]/50 text-sm font-bold">
                                {{ mb_substr($anggota->nama_lengkap, 0, 1) }}
                            </div>
                        @endif
                    </button>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 z-50 mt-2 w-48 rounded-xl bg-[#04241e] border border-slate-700/60 p-2 shadow-xl text-left"
                         style="display: none;">
                        <div class="px-4 py-2 border-b border-slate-700/40 mb-1">
                            <p class="text-xs text-slate-400 font-medium">Masuk sebagai</p>
                            <p class="text-xs text-white font-bold truncate">{{ Auth::user()->name }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">
                            <i class="fa-regular fa-user mr-2 text-xs"></i> Edit Profil
                        </a>
                        <a href="{{ route('anggota.e-kartu') }}" class="block rounded-lg px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition">
                            <i class="fa-regular fa-id-card mr-2 text-xs"></i> E-Kartu Saya
                        </a>
                    </div>
                </div>

                <!-- Signout Icon Button -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-slate-300 hover:text-white transition" title="Keluar">
                        <i class="fa-solid fa-right-from-bracket text-lg"></i>
                    </button>
                </form>
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex items-center md:hidden gap-4">
                <div class="relative text-slate-300 hover:text-white transition cursor-pointer">
                    <i class="fa-regular fa-bell text-lg"></i>
                </div>
                
                <button @click="mobileMenuOpen = ! mobileMenuOpen" class="text-slate-300 hover:text-white focus:outline-none">
                    <i class="fa-solid fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                    <i class="fa-solid fa-xmark text-xl" x-show="mobileMenuOpen" style="display: none;"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu panel -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden border-t border-slate-700/60 bg-[#04241e] px-6 py-4 space-y-3"
             style="display: none;">
            <a href="{{ route('landing') }}" class="block text-slate-300 hover:text-white font-semibold py-2">Beranda</a>
            <a href="{{ route('katalog') }}" class="block text-slate-300 hover:text-white font-semibold py-2">Katalog</a>
            <a href="{{ route('landing') }}#koleksi" class="block text-slate-300 hover:text-white font-semibold py-2">Layanan</a>
            <a href="{{ route('landing') }}#kontak" class="block text-slate-300 hover:text-white font-semibold py-2">Fasilitas</a>
            <a href="{{ route('anggota.e-kartu') }}" class="block text-[#ffdc7c] font-semibold py-2">Profil</a>
            
            <div class="border-t border-slate-700/40 pt-3 flex items-center justify-between">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 text-slate-300 hover:text-white font-semibold py-2">
                    @if ($anggota->foto)
                        <img src="{{ asset('storage/'.$anggota->foto) }}" alt="Foto" class="h-8 w-8 rounded-full object-cover">
                    @else
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#ffdc7c]/20 text-[#ffdc7c] text-xs font-bold">
                            {{ mb_substr($anggota->nama_lengkap, 0, 1) }}
                        </div>
                    @endif
                    <span>Edit Profil</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-slate-300 hover:text-white font-semibold flex items-center gap-2 py-2">
                        <i class="fa-solid fa-right-from-bracket"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="print-container">
        <div class="mx-auto max-w-7xl px-6 lg:px-12 py-16">
            
            <!-- Page Title Section -->
            <div class="text-center mb-12 no-print">
                <h1 class="font-serif text-4xl lg:text-5xl font-bold text-[#04241e]">E-Kartu Anggota</h1>
                <p class="mt-4 text-slate-500 text-sm max-w-xl mx-auto leading-relaxed">
                    Kartu identitas digital Anda untuk mengakses seluruh layanan dan fasilitas Perpustakaan Kota Bukittinggi.
                </p>
            </div>

            <!-- Card Container Box -->
            <div class="bg-[#f0f2f5] border border-slate-200/60 rounded-[2.5rem] p-8 lg:p-12 shadow-sm">
                <div class="grid grid-cols-1 min-[1200px]:grid-cols-[auto_1fr] gap-12 xl:gap-16 items-start">
                    
                    <!-- Left: Card Display Wrapper -->
                    <div class="flex items-center justify-center min-[1200px]:justify-start print-card-wrapper w-full">
                        <div class="w-full max-w-[580px] e-card-element overflow-hidden rounded-[2rem] bg-[#061b3a] p-8 text-white shadow-2xl shadow-[#061b3a]/20">
                            <div class="flex flex-col justify-between gap-8 sm:flex-row">
                                <div class="flex-1">
                                    <p class="text-sm font-bold uppercase tracking-[0.24em] text-[#ffdc7c]">SIPADI Bukittinggi</p>
                                    <h3 class="mt-3 text-3xl font-bold">Kartu Anggota Digital</h3>

                                    <dl class="mt-8 grid gap-5 rounded-3xl bg-white/10 p-6 text-sm">
                                        <div>
                                            <dt class="text-slate-300">Nama Anggota</dt>
                                            <dd class="mt-1 text-2xl font-bold">{{ $anggota->nama_lengkap }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-slate-300">Nomor Kartu / NIK</dt>
                                            <dd class="mt-1 font-mono text-xl font-bold">{{ $eKartu->no_anggota }}</dd>
                                        </div>
                                        <div class="grid gap-4 sm:grid-cols-2">
                                            <div>
                                                <dt class="text-slate-300">Kalangan</dt>
                                                <dd class="mt-1 font-semibold">{{ $eKartu->kalangan }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-slate-300">Berlaku Sampai</dt>
                                                <dd class="mt-1 font-semibold">{{ $eKartu->masa_berlaku?->translatedFormat('d F Y') }}</dd>
                                            </div>
                                        </div>
                                    </dl>
                                </div>

                                <div class="flex min-w-56 flex-col justify-between rounded-3xl bg-white p-5 text-[#061b3a]">
                                    <div class="flex justify-center">
                                        @if ($anggota->foto)
                                            <img src="{{ asset('storage/'.$anggota->foto) }}" alt="Foto {{ $anggota->nama_lengkap }}" class="h-28 w-28 rounded-3xl object-cover ring-4 ring-[#ffdc7c]/70">
                                        @else
                                            <div class="flex h-28 w-28 items-center justify-center rounded-3xl bg-[#f6f5e9] text-4xl font-bold ring-4 ring-[#ffdc7c]/70">
                                                {{ mb_substr($anggota->nama_lengkap, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-6 rounded-2xl bg-[#f6f5e9] p-4 text-center">
                                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Kode Kartu</p>
                                        <p class="mt-2 break-all font-mono text-xs">{{ $eKartu->barcode }}</p>
                                    </div>
                                    <div class="mt-4 rounded-full bg-emerald-50 px-4 py-2 text-center text-sm font-bold text-emerald-700">
                                        Aktif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Info Panel & Action Buttons -->
                    <div class="flex flex-col gap-8 no-print h-full justify-between w-full max-w-xl mx-auto min-[1200px]:mx-0">
                        
                        <!-- Status Keanggotaan Card -->
                        <div class="bg-white border border-slate-100 rounded-[1.8rem] p-8 shadow-sm flex-1">
                            <div class="flex items-center gap-3">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                                    <i class="fa-solid fa-circle-check text-2xl"></i>
                                </span>
                                <h3 class="text-xl font-bold text-[#04241e]">Status Keanggotaan</h3>
                            </div>

                            <div class="flex items-center gap-2 mt-5">
                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200/50 px-4 py-1 text-xs font-bold rounded-full">
                                    Aktif
                                </span>
                                <span class="text-slate-400 text-sm font-semibold ml-2">
                                    Reguler
                                </span>
                            </div>

                            <hr class="border-slate-100/80 my-6">

                            <p class="text-slate-500 text-sm leading-relaxed">
                                Tunjukkan E-Kartu ini atau pindai QR Code pada mesin kiosk di perpustakaan untuk meminjam buku dan menggunakan fasilitas.
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Download PDF (or PNG) -->
                            <a href="{{ route('anggota.e-kartu.download') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-[#04241e] hover:bg-[#06342c] px-6 py-4 text-base font-bold text-white shadow-md hover:shadow-lg transition duration-300">
                                <i class="fa-solid fa-download"></i>
                                Unduh PDF
                            </a>

                            <!-- Print Card -->
                            <button onclick="window.print()" class="flex items-center justify-center gap-2 rounded-2xl border border-[#04241e] hover:bg-slate-50 bg-white px-6 py-4 text-base font-bold text-[#04241e] shadow-sm hover:shadow-md transition duration-300">
                                <i class="fa-solid fa-print"></i>
                                Cetak Kartu
                            </button>
                        </div>

                        <!-- Problem/Help Link -->
                        <div class="text-center mt-2">
                            <a href="{{ route('landing') }}#kontak" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-slate-600 transition">
                                <i class="fa-regular fa-circle-question text-sm"></i>
                                Masalah dengan kartu Anda?
                            </a>
                        </div>
                        
                    </div>

                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 no-print">
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
