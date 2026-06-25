<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $buku->judul }} - SIPADI Bukittinggi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f5e9] font-sans text-[#061b3a] antialiased">

    <!-- Header / Navbar -->
    @include('layouts.public_navbar')

    <!-- Breadcrumbs -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 py-6">
        <nav class="flex text-xs font-semibold text-slate-400 items-center gap-2">
            <a href="{{ route('landing') }}" class="hover:text-[#04241e] transition">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
            <a href="{{ route('katalog') }}" class="hover:text-[#04241e] transition">Katalog</a>
            @if($buku->kategori)
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
                <a href="{{ route('katalog', ['kategori[]' => $buku->id_kategori]) }}" class="hover:text-[#04241e] transition">{{ $buku->kategori->nama_kategori }}</a>
            @endif
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
            <span class="text-slate-600 line-clamp-1">{{ $buku->judul }}</span>
        </nav>
    </div>

    <!-- Main Detail Section -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 pb-16">
        <!-- Main Info Card -->
        <div class="bg-white rounded-[2rem] p-6 lg:p-10 border border-slate-100 shadow-sm grid gap-10 lg:grid-cols-[280px_1fr] items-start">
            <!-- Left Side Cover & Action -->
            <div class="space-y-6">
                <!-- Cover Image Box -->
                <div class="relative w-full h-[360px] rounded-3xl bg-slate-100 shadow-sm overflow-hidden flex flex-col items-center justify-center">
                    @if($buku->gambar_cover)
                        @php
                            $imageUrl = str_starts_with($buku->gambar_cover, 'http') ? $buku->gambar_cover : asset('storage/' . $buku->gambar_cover);
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $buku->judul }}" class="w-full h-full object-cover">
                    @else
                        <!-- Fallback Dynamic CSS Cover -->
                        <div class="absolute inset-0 bg-gradient-to-br from-[#04241e] to-[#0a4b3f] p-6 text-white flex flex-col justify-between">
                            <div class="absolute left-0 top-0 bottom-0 w-3.5 bg-gradient-to-r from-black/20 to-transparent"></div>
                            <div class="mt-8">
                                <h3 class="font-serif font-bold text-xl leading-snug">{{ $buku->judul }}</h3>
                            </div>
                            <p class="text-xs text-slate-300">{{ $buku->penulis }}</p>
                        </div>
                    @endif

                    <!-- Availability Badge inside Cover Box -->
                    <div class="absolute top-4 left-4 {{ $status === 'Tersedia' ? 'bg-emerald-500/90' : 'bg-orange-500/90' }} backdrop-blur-sm text-white text-[9px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                        <i class="fa-solid fa-circle text-[7px] mr-1.5 align-middle"></i>
                        {{ $status }}
                    </div>
                </div>

                <!-- Borrow Action Button -->
                <div class="space-y-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="w-full bg-[#04241e] hover:bg-[#063b31] text-white font-bold rounded-xl py-3.5 px-4 flex items-center justify-center gap-2.5 transition duration-200 text-sm shadow-md shadow-[#04241e]/10">
                            <i class="fa-solid fa-right-to-bracket text-base"></i>
                            Ajukan Peminjaman
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full bg-[#04241e] hover:bg-[#063b31] text-white font-bold rounded-xl py-3.5 px-4 flex items-center justify-center gap-2.5 transition duration-200 text-sm shadow-md shadow-[#04241e]/10">
                            <i class="fa-solid fa-right-to-bracket text-base"></i>
                            Login untuk Meminjam
                        </a>
                    @endauth

                    <!-- Info Box -->
                    <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-4 text-xs leading-relaxed text-blue-700 flex items-start gap-3">
                        <i class="fa-solid fa-circle-info text-base text-blue-500 shrink-0 mt-0.5"></i>
                        <p>Anda harus masuk sebagai anggota aktif untuk melakukan reservasi atau peminjaman buku ini.</p>
                    </div>
                </div>
            </div>

            <!-- Right Side Book Information -->
            <div class="space-y-8">
                <!-- Meta tags & Title -->
                <div>
                    <!-- Categories -->
                    <div class="flex items-center gap-2 mb-4">
                        <span class="bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-md">
                            {{ $buku->kategori?->nama_kategori ?? 'Umum' }}
                        </span>
                        @if($buku->kategori?->nama_kategori == 'Fiksi')
                            <span class="bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-md">
                                Sastra Indonesia
                            </span>
                        @endif
                    </div>

                    <!-- Book Title -->
                    <h1 class="font-serif text-3xl lg:text-4xl font-bold text-[#04241e] leading-tight">{{ $buku->judul }}</h1>
                    
                    <!-- Book Author -->
                    <div class="flex items-center gap-2 text-slate-500 text-sm mt-3 font-semibold">
                        <i class="fa-solid fa-user-pen text-slate-400 text-xs"></i>
                        <span>{{ $buku->penulis }}</span>
                    </div>
                </div>

                <!-- Rating & Format Cards -->
                <div class="grid grid-cols-2 gap-4 max-w-md">
                    <!-- Rating Card -->
                    <div class="bg-[#f0f5f4] rounded-2xl p-4 flex items-center gap-4 border border-[#e2e8e7]">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-yellow-400 text-white text-lg">
                            <i class="fa-solid fa-star"></i>
                        </span>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rating</p>
                            <p class="text-sm font-bold text-[#061b3a] mt-0.5">{{ $buku->rating }} / 5.0</p>
                        </div>
                    </div>

                    <!-- Format Card -->
                    <div class="bg-[#f0f5f4] rounded-2xl p-4 flex items-center gap-4 border border-[#e2e8e7]">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#04241e] text-white text-lg">
                            <i class="fa-solid fa-book-open"></i>
                        </span>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Format</p>
                            <p class="text-sm font-bold text-[#061b3a] mt-0.5">Buku Cetak</p>
                        </div>
                    </div>
                </div>

                <!-- Detail Fields Table -->
                <div class="border-t border-slate-100 pt-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ISBN</p>
                            <p class="text-sm font-semibold text-[#061b3a] mt-1">{{ $buku->isbn ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Penerbit</p>
                            <p class="text-sm font-semibold text-[#061b3a] mt-1">{{ $buku->penerbit ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tahun Terbit</p>
                            <p class="text-sm font-semibold text-[#061b3a] mt-1">{{ $buku->tahun_terbit ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jumlah Halaman</p>
                            <p class="text-sm font-semibold text-[#061b3a] mt-1">{{ $buku->jumlah_halaman }} Halaman</p>
                        </div>
                    </div>
                </div>

                <!-- Location Box -->
                <div class="bg-[#eff6ff] rounded-2xl p-5 border border-[#dbeafe] flex items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-500 text-white text-xl">
                        <i class="fa-solid fa-map-pin"></i>
                    </span>
                    <div>
                        <p class="text-[9px] font-bold text-blue-400 uppercase tracking-wider">Lokasi Fisik Koleksi</p>
                        <p class="text-lg font-bold text-blue-900 mt-0.5">{{ $lokasi_rak }}</p>
                    </div>
                </div>

                <!-- Synopsis -->
                <div class="border-t border-slate-100 pt-6 space-y-3">
                    <h3 class="font-serif font-bold text-xl text-[#04241e] flex items-center gap-2">
                        <i class="fa-solid fa-quote-left text-sm text-[#04241e]/40"></i>
                        Sinopsis
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">{{ $buku->deskripsi }}</p>
                </div>
            </div>
        </div>

        <!-- Related Recommendations -->
        @if($recommendations->isNotEmpty())
            <div class="mt-16 space-y-6">
                <!-- Section Header -->
                <div class="flex items-end justify-between border-b border-slate-200/60 pb-6">
                    <h2 class="font-serif text-2xl lg:text-3xl font-bold text-[#04241e] flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#04241e] text-[#ffdc7c] text-sm">
                            <i class="fa-solid fa-tags"></i>
                        </span>
                        Rekomendasi Terkait
                    </h2>
                    <a href="{{ route('katalog') }}" class="text-sm font-bold text-[#04241e] hover:underline flex items-center gap-1.5 transition">
                        Lihat Semua
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <!-- Recommended Grid -->
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-5">
                    @foreach($recommendations as $recom)
                        @php
                            $recomTersedia = $recom->eksemplar_tersedia_count > 0;
                        @endphp
                        <a href="{{ route('katalog.show', $recom->id_buku) }}" 
                           class="group bg-white rounded-2xl p-3 border border-slate-100 shadow-sm hover:shadow-md hover:scale-[1.01] transition duration-200 flex flex-col justify-between">
                            <div>
                                <!-- Styled Book Cover -->
                                <div class="relative w-full h-[180px] rounded-xl bg-slate-100 overflow-hidden mb-3 flex flex-col items-center justify-center">
                                    @if($recom->gambar_cover)
                                        @php
                                            $recomImageUrl = str_starts_with($recom->gambar_cover, 'http') ? $recom->gambar_cover : asset('storage/' . $recom->gambar_cover);
                                        @endphp
                                        <img src="{{ $recomImageUrl }}" alt="{{ $recom->judul }}" class="w-full h-full object-cover">
                                    @else
                                        <!-- Fallback cover -->
                                        <div class="absolute inset-0 bg-gradient-to-br from-[#04241e] to-[#0a4b3f] p-3 text-white flex flex-col justify-between">
                                            <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-gradient-to-r from-black/20 to-transparent"></div>
                                            <h4 class="font-serif font-bold text-xs leading-snug mt-2">{{ $recom->judul }}</h4>
                                            <p class="text-[9px] text-slate-300">{{ $recom->penulis }}</p>
                                        </div>
                                    @endif

                                    <!-- Status Badge -->
                                    <div class="absolute top-2 right-2 {{ $recomTersedia ? 'bg-emerald-500/90 text-white' : 'bg-orange-500/90 text-white' }} backdrop-blur-sm text-[8px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                        {{ $recomTersedia ? 'Tersedia' : 'Dipinjam' }}
                                    </div>
                                </div>

                                <h4 class="font-bold text-xs text-[#061b3a] group-hover:text-[#04241e] transition line-clamp-2 leading-snug">
                                    {{ $recom->judul }}
                                </h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $recom->penulis }}</p>
                            </div>

                            <!-- Bottom Status Info -->
                            <div class="flex items-center justify-between mt-3 pt-2 border-t text-[10px] font-semibold text-slate-400">
                                <span>{{ $recom->tahun_terbit }}</span>
                                <span class="{{ $recomTersedia ? 'text-emerald-600' : 'text-orange-600' }}">
                                    <i class="fa-solid fa-circle text-[6px] mr-1 align-middle"></i>
                                    {{ $recomTersedia ? 'Tersedia' : 'Dipinjam' }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/60 py-12">
        <div class="mx-auto max-w-7xl px-6 lg:px-12 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h3 class="font-serif font-bold text-xl text-[#04241e]">SIPADI Bukittinggi</h3>
                <p class="text-slate-500 text-sm mt-2 max-w-md">Sistem Informasi Perpustakaan & Arsip Digital Kota Bukittinggi. Menghubungkan masyarakat dengan warisan budaya dan literasi terbaik.</p>
            </div>
            <div class="flex flex-wrap gap-6 text-sm font-semibold text-slate-600">
                <a href="#" class="hover:text-[#04241e] transition">Tentang Kami</a>
                <a href="#" class="hover:text-[#04241e] transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-[#04241e] transition">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-[#04241e] transition">Peta Situs</a>
            </div>
        </div>
        <div class="mx-auto max-w-7xl px-6 lg:px-12 mt-8 pt-6 border-t border-slate-100 text-center text-xs text-slate-400">
            &copy; 2026 Dinas Perpustakaan dan Kearsipan Kota Bukittinggi. Seluruh Hak Cipta Dilindungi.
        </div>
    </footer>

</body>
</html>
