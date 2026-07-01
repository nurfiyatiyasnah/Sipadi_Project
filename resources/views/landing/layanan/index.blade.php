<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Perpustakaan - SIPADI Bukittinggi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f5e9] font-sans text-[#061b3a] antialiased">
    @include('layouts.public_navbar')

    <!-- Header / Title Section -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 py-10">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-emerald-800">Layanan</p>
                <h1 class="font-serif text-4xl lg:text-5xl font-bold text-[#04241e] mt-2">Layanan Perpustakaan</h1>
                <p class="text-slate-500 mt-3 text-sm lg:text-base max-w-2xl">
                    Jelajahi berbagai layanan unggulan kami yang dirancang untuk mendukung kebutuhan literasi, riset, dan rekreasi edukatif Anda di lingkungan kota Bukittinggi yang kaya akan warisan budaya.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 pb-16">
        <section class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($layanan as $item)
                @php
                    $icons = ['fa-regular fa-id-card', 'fa-solid fa-book-open-reader', 'fa-solid fa-arrow-left', 'fa-solid fa-laptop-file', 'fa-solid fa-graduation-cap', 'fa-solid fa-flag'];
                    $icon = $icons[$loop->index % count($icons)];
                @endphp

                <article class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
                    <div>
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-[#04241e]">
                            <i class="{{ $icon }} text-lg"></i>
                        </span>
                        <h2 class="mt-6 font-serif text-xl font-bold leading-snug text-[#04241e]">{{ $item->nama_layanan }}</h2>
                        <p class="mt-3 text-slate-500 text-sm leading-relaxed">{{ Str::limit(strip_tags($item->deskripsi) ?: 'Informasi layanan perpustakaan SIPADI Bukittinggi.', 155) }}</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a href="{{ route('layanan.show', $item->slug) }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#04241e] hover:text-emerald-800 transition group">
                            Lihat Detail
                            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition duration-150"></i>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-3xl border border-slate-100 bg-white p-12 text-center shadow-sm">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400 text-3xl mb-4">
                        <i class="fa-solid fa-handshake-angle"></i>
                    </span>
                    <h3 class="font-serif text-lg font-bold text-[#04241e]">Belum ada layanan aktif</h3>
                    <p class="text-slate-500 text-sm mt-1 max-w-xl mx-auto">Layanan yang ditampilkan di sini akan otomatis mengikuti data aktif yang dikelola oleh petugas.</p>
                </div>
            @endforelse
        </section>
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
                    <a href="{{ route('layanan.index') }}" class="hover:text-[#04241e] transition">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-[#04241e] transition">Peta Situs</a>
                    <a href="{{ route('landing') }}#kontak" class="hover:text-[#04241e] transition">Hubungi Kami</a>
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
