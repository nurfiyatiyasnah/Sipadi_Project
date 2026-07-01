<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $layanan->nama_layanan }} - SIPADI Bukittinggi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f5e9] font-sans text-[#061b3a] antialiased">
    @include('layouts.public_navbar')

    <!-- Breadcrumb Area -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 pt-10">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('landing') }}" class="hover:text-[#04241e] transition">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <a href="{{ route('layanan.index') }}" class="hover:text-[#04241e] transition">Layanan</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="text-slate-700 truncate max-w-[200px] sm:max-w-xs md:max-w-md">{{ $layanan->nama_layanan }}</span>
        </nav>
    </div>

    <div class="mx-auto max-w-7xl px-6 lg:px-12 py-8 grid gap-8 lg:grid-cols-[1fr_390px]">
        <!-- Left Column: Layanan Detail -->
        <main>
            <article class="bg-white rounded-3xl p-6 lg:p-10 border border-slate-100 shadow-sm space-y-8">
                <!-- Header / Title -->
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
                        <span class="bg-[#04241e] text-[#ffdc7c] text-[10px] font-bold px-3 py-1 rounded-md uppercase tracking-wider">
                            Layanan
                        </span>
                    </div>
                    
                    <h1 class="font-serif text-3xl lg:text-4xl font-bold leading-tight text-[#04241e]">
                        {{ $layanan->nama_layanan }}
                    </h1>
                </div>

                <!-- Description -->
                <div class="text-[#061b3a] text-sm lg:text-base leading-relaxed pt-6 border-t border-slate-100/60 font-sans">
                    <p>{{ $layanan->deskripsi ?: 'Informasi detail layanan Perpustakaan Umum Kota Bukittinggi.' }}</p>
                </div>

                <!-- Key Parameters Row -->
                <div class="grid gap-4 md:grid-cols-3 pt-4">
                    <div class="rounded-2xl border border-slate-100 bg-[#fbfbfa] p-5">
                        <div class="flex items-center gap-2 text-slate-500">
                            <i class="fa-regular fa-clock text-sm text-[#04241e]"></i>
                            <span class="text-xs font-semibold">Jam Layanan</span>
                        </div>
                        <p class="mt-2 text-lg font-bold text-[#04241e] leading-snug">{{ $layanan->jam_layanan ?: 'Menyesuaikan jadwal' }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-[#fbfbfa] p-5">
                        <div class="flex items-center gap-2 text-slate-500">
                            <i class="fa-solid fa-headset text-sm text-[#04241e]"></i>
                            <span class="text-xs font-semibold">Kontak</span>
                        </div>
                        <p class="mt-2 text-lg font-bold text-[#04241e] leading-snug">{{ $layanan->kontak_layanan ?: 'Petugas layanan' }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-[#fbfbfa] p-5">
                        <div class="flex items-center gap-2 text-slate-500">
                            <i class="fa-solid fa-money-bill-wave text-sm text-[#04241e]"></i>
                            <span class="text-xs font-semibold">Biaya</span>
                        </div>
                        <p class="mt-2 text-lg font-bold text-[#04241e] leading-snug">{{ $layanan->biaya ?: 'Gratis' }}</p>
                    </div>
                </div>

                <!-- Alur Layanan -->
                <div class="pt-6 border-t border-slate-100/60">
                    <h2 class="font-serif text-xl font-bold text-[#04241e] mb-6">Alur Layanan</h2>
                    <div class="space-y-4">
                        @forelse ($procedures as $procedure)
                            <div class="relative flex gap-4">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#04241e] text-sm font-bold text-[#ffdc7c]">{{ $loop->iteration }}</span>
                                <div class="flex-1 rounded-2xl border border-slate-100 bg-slate-50/50 px-5 py-4">
                                    <h3 class="font-bold text-[#04241e]">Langkah {{ $loop->iteration }}</h3>
                                    <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $procedure }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="relative flex gap-4">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#04241e] text-sm font-bold text-[#ffdc7c]">1</span>
                                <div class="flex-1 rounded-2xl border border-slate-100 bg-slate-50/50 px-5 py-4">
                                    <h3 class="font-bold text-[#04241e]">Hubungi Petugas</h3>
                                    <p class="mt-1 text-sm leading-relaxed text-slate-600">Silakan hubungi petugas untuk mendapatkan arahan penggunaan layanan ini.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </article>
        </main>

        <!-- Right Column: Sidebar -->
        <aside class="space-y-6">
            <!-- Action / Cover Box -->
            <div class="rounded-3xl border border-slate-100 bg-white p-6 text-center shadow-sm">
                <div class="mx-auto flex h-32 w-32 items-center justify-center overflow-hidden bg-slate-50 rounded-2xl border border-slate-100">
                    @if ($layanan->gambar)
                        <img src="{{ Storage::url($layanan->gambar) }}" alt="{{ $layanan->nama_layanan }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full w-full bg-emerald-50 text-emerald-800 text-5xl">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                    @endif
                </div>
                <h2 class="mt-5 font-serif text-lg font-bold text-[#04241e]">Ingin Menggunakan Layanan?</h2>
                <p class="mx-auto mt-2 max-w-xs text-sm leading-relaxed text-slate-500">Anda dapat masuk sebagai anggota aktif untuk menggunakan layanan ini secara online.</p>
                <div class="mt-6 space-y-3">
                    <a href="{{ route('login') }}" class="block rounded-2xl bg-[#04241e] py-3 text-sm font-bold text-white hover:bg-opacity-90 transition duration-200">Masuk ke Akun</a>
                    <a href="{{ route('register') }}" class="block rounded-2xl border border-[#04241e] py-3 text-sm font-bold text-[#04241e] hover:bg-emerald-50 transition duration-200">Daftar Anggota Baru</a>
                </div>
            </div>

            <!-- Syarat & Ketentuan -->
            <div id="syarat-ketentuan" class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <h2 class="flex items-center gap-2 font-serif text-base font-bold text-[#04241e] border-b pb-4 mb-4">
                    <i class="fa-solid fa-list-check text-sm text-emerald-800"></i>
                    Syarat & Ketentuan
                </h2>
                <ul class="space-y-3 text-sm leading-relaxed text-slate-500 list-disc list-inside">
                    @forelse ($requirements as $requirement)
                        <li>{{ $requirement }}</li>
                    @empty
                        <li>Memiliki akun atau kartu anggota perpustakaan yang masih aktif.</li>
                        <li>Mengikuti ketentuan yang berlaku pada layanan ini.</li>
                    @endforelse
                </ul>
            </div>

            <!-- Jam Layanan Sidebar -->
            <div id="jadwal-layanan" class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <h2 class="flex items-center gap-2 font-serif text-base font-bold text-[#04241e] border-b pb-4 mb-4">
                    <i class="fa-regular fa-clock text-sm text-emerald-800"></i>
                    Jam Layanan
                </h2>
                <dl class="space-y-3 text-sm text-slate-500">
                    <div class="flex items-center justify-between gap-4">
                        <dt class="font-semibold">Waktu Layanan</dt>
                        <dd class="text-right font-bold text-[#04241e]">{{ $layanan->jam_layanan ?: 'Mengikuti jam operasional' }}</dd>
                    </div>
                    @if ($layanan->kontak_layanan)
                        <div class="flex items-center justify-between gap-4">
                            <dt class="font-semibold">Kontak</dt>
                            <dd class="text-right font-bold text-[#04241e]">{{ $layanan->kontak_layanan }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Related Layanan -->
            @if ($relatedLayanan->isNotEmpty())
                <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <h2 class="font-serif text-base font-bold text-[#04241e] border-b pb-4 mb-4">Layanan Lainnya</h2>
                    <div class="space-y-3">
                        @foreach ($relatedLayanan as $item)
                            <a href="{{ route('layanan.show', $item->slug) }}" class="block rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-[#04241e] hover:bg-emerald-50 hover:border-emerald-200 transition duration-200">
                                {{ $item->nama_layanan }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 mt-12">
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
