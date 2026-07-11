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
        <nav class="flex text-xs font-semibold text-slate-400 items-center gap-1.5">
            <a href="{{ route('katalog') }}" class="hover:text-[#04241e] transition">Katalog</a>
            @if($buku->kategori)
                <span class="text-slate-300 font-normal text-sm">&rsaquo;</span>
                <a href="{{ route('katalog', ['kategori[]' => $buku->id_kategori]) }}" class="hover:text-[#04241e] transition">{{ $buku->kategori->nama_kategori }}</a>
            @endif
            <span class="text-slate-300 font-normal text-sm">&rsaquo;</span>
            <span class="text-slate-600 line-clamp-1 font-bold">{{ $buku->judul }}</span>
        </nav>
    </div>

    <!-- Main Detail Section -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 pb-16">
        <div class="grid min-w-0 grid-cols-1 gap-8 lg:grid-cols-[300px_1fr] items-start">
            
            <!-- Left Side Cover & Action -->
            <div class="min-w-0 space-y-6">
                <!-- Cover Image Card -->
                <div class="flex min-w-0 items-center justify-center rounded-[2rem] border border-slate-100/80 bg-white p-6 shadow-sm">
                    <div class="relative w-full aspect-[3/4] rounded-2xl bg-slate-50 overflow-hidden flex items-center justify-center shadow-md">
                        @if($buku->gambar_cover)
                            @php
                                $imageUrl = str_starts_with($buku->gambar_cover, 'http') ? $buku->gambar_cover : asset('storage/' . $buku->gambar_cover);
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $buku->judul }}" class="w-full h-full object-cover">
                        @else
                            <!-- Fallback Dynamic CSS Cover -->
                            <div class="absolute inset-0 bg-gradient-to-br from-[#1e463c] to-[#0f3028] p-6 text-white flex flex-col justify-between rounded-xl">
                                <div class="absolute left-0 top-0 bottom-0 w-3.5 bg-gradient-to-r from-black/20 to-transparent"></div>
                                <div class="mt-8">
                                    <h3 class="break-words font-sans font-bold text-lg leading-snug">{{ $buku->judul }}</h3>
                                </div>
                                <p class="break-words text-xs text-slate-300 font-semibold">{{ $buku->penulis }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status & Borrow Action Card -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-6">
                    <!-- Status Ketersediaan -->
                    <div>
                        <h4 class="text-xs font-semibold text-slate-400 mb-2">Status Ketersediaan</h4>
                        @php
                            $availabilityStatus = $buku->statusKetersediaan(false);
                            $tersediaCount = $buku->availableEksemplarCount();
                            $canBorrow = $availabilityStatus === 'tersedia';
                        @endphp
                        @if($availabilityStatus === 'tersedia')
                            <div class="flex items-center gap-2 text-sm font-bold text-[#1e463c]">
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                                Tersedia {{ $tersediaCount }} Eksemplar
                            </div>
                        @elseif($availabilityStatus === 'dipinjam_semua')
                            <div class="flex items-center gap-2 text-sm font-bold text-orange-600">
                                <span class="h-2.5 w-2.5 rounded-full bg-orange-500 shrink-0"></span>
                                Dipinjam Semua
                            </div>
                        @elseif($availabilityStatus === 'stok_kosong')
                            <div class="flex items-center gap-2 text-sm font-bold text-slate-600">
                                <span class="h-2.5 w-2.5 rounded-full bg-slate-500 shrink-0"></span>
                                Stok Kosong
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-sm font-bold text-rose-600">
                                <span class="h-2.5 w-2.5 rounded-full bg-rose-500 shrink-0"></span>
                                Tidak Tersedia
                            </div>
                        @endif
                    </div>

                    <hr class="border-slate-100">

                    <!-- Actions -->
                    <div class="space-y-3">
                        @if($canBorrow)
                            @auth
                                <a href="{{ route('peminjaman.create', $buku->id_buku) }}" class="w-full bg-[#1e463c] hover:bg-[#15332c] text-white font-bold rounded-xl py-3.5 px-4 flex items-center justify-center gap-2.5 transition duration-200 text-sm shadow-sm">
                                    <i class="fa-solid fa-file-circle-plus text-base"></i>
                                    Ajukan Peminjaman
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="w-full bg-[#1e463c] hover:bg-[#15332c] text-white font-bold rounded-xl py-3.5 px-4 flex items-center justify-center gap-2.5 transition duration-200 text-sm shadow-sm">
                                    <i class="fa-solid fa-file-circle-plus text-base"></i>
                                    Ajukan Peminjaman
                                </a>
                            @endauth
                        @else
                            <button type="button" disabled class="w-full cursor-not-allowed bg-slate-100 text-slate-400 border border-slate-200 font-bold rounded-xl py-3.5 px-4 flex items-center justify-center gap-2.5 text-sm shadow-sm">
                                <i class="fa-solid fa-circle-minus text-base"></i>
                                Tidak Bisa Dipinjam
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Side Book Information -->
            <div class="min-w-0 space-y-8 lg:pl-4">
                <!-- Badges -->
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="bg-[#e2f0d9] text-[#385723] px-3 py-1.5 text-xs font-semibold rounded-md">
                        {{ $buku->kategori?->nama_kategori ?? 'Umum' }}
                    </span>
                </div>

                <!-- Title & Author -->
                <div class="space-y-2">
                    <h1 class="break-words text-3xl lg:text-4xl font-bold text-[#04241e] leading-tight font-sans">
                        {{ $buku->judul }}
                    </h1>
                    <p class="break-words text-slate-600 text-sm font-semibold">
                        Oleh: <span class="text-slate-800">{{ $buku->penulis ?? 'Anonim' }}</span>
                    </p>
                </div>

                <!-- Synopsis -->
                <div class="space-y-3">
                    <h2 class="text-xl font-bold text-[#04241e]">Sinopsis</h2>
                    <p class="break-words text-slate-600 text-sm leading-relaxed whitespace-pre-line">{{ $buku->deskripsi }}</p>
                </div>

                <!-- Informasi Detail Grid -->
                <div class="space-y-4">
                    <h2 class="text-xl font-bold text-[#04241e]">Informasi Detail</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Penerbit -->
                        <div class="flex min-w-0 flex-col justify-center min-h-[72px] rounded-xl border border-slate-100 bg-white p-4 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Penerbit</span>
                            <span class="mt-1 break-words text-sm font-bold text-slate-700">{{ $buku->penerbit ?? '-' }}</span>
                        </div>
                        
                        <!-- Tahun Terbit -->
                        <div class="flex min-w-0 flex-col justify-center min-h-[72px] rounded-xl border border-slate-100 bg-white p-4 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tahun Terbit</span>
                            <span class="text-sm font-bold text-slate-700 mt-1">{{ $buku->tahun_terbit ?? '-' }}</span>
                        </div>
                        
                        <!-- ISBN -->
                        <div class="flex min-w-0 flex-col justify-center min-h-[72px] rounded-xl border border-slate-100 bg-white p-4 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ISBN</span>
                            <span class="mt-1 break-all text-sm font-bold text-slate-700">{{ $buku->isbn ?? '-' }}</span>
                        </div>
                        
                        <!-- Lokasi Rak -->
                        <div class="flex min-w-0 flex-col justify-center min-h-[72px] rounded-xl border border-slate-100 bg-white p-4 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lokasi Rak</span>
                            <span class="mt-1 break-words text-sm font-bold text-slate-700">{{ $lokasi_rak }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Recommendations -->
        @if($recommendations->isNotEmpty())
            <div class="mt-16 space-y-6">
                <!-- Section Header -->
                <div class="flex items-center justify-between border-t border-slate-200/60 pt-10 mb-6">
                    <h3 class="text-xl font-bold text-[#04241e]">Buku Terkait</h3>
                    <a href="{{ route('katalog') }}" class="text-sm font-bold text-[#04241e] hover:underline flex items-center gap-1 transition">
                        Lihat Semua
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <!-- Recommended Grid -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5">
                    @foreach($recommendations as $recom)
                        <a href="{{ route('katalog.show', $recom->id_buku) }}" 
                           class="group bg-white rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col">
                            <!-- Styled Book Cover Padded Area -->
                            <div class="w-full aspect-[3/4] rounded-xl bg-[#f2ede2] p-4 flex items-center justify-center mb-3 overflow-hidden shadow-inner">
                                @if($recom->gambar_cover)
                                    @php
                                        $recomImageUrl = str_starts_with($recom->gambar_cover, 'http') ? $recom->gambar_cover : asset('storage/' . $recom->gambar_cover);
                                    @endphp
                                    <img src="{{ $recomImageUrl }}" alt="{{ $recom->judul }}" class="h-full object-contain rounded shadow-md group-hover:scale-105 transition duration-300">
                                @else
                                    <!-- Fallback Cover style -->
                                    <div class="w-full h-full bg-gradient-to-br from-[#1e463c] to-[#0f3028] text-white p-3 flex flex-col justify-between rounded shadow-md">
                                        <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-gradient-to-r from-black/20 to-transparent"></div>
                                        <h4 class="font-sans font-bold text-[10px] leading-snug line-clamp-3">{{ $recom->judul }}</h4>
                                        <p class="text-[8px] text-slate-300 font-semibold">{{ $recom->penulis }}</p>
                                    </div>
                                @endif
                            </div>

                            <h4 class="break-words font-bold text-xs text-[#061b3a] group-hover:text-[#04241e] transition line-clamp-1">
                                {{ $recom->judul }}
                            </h4>
                            <p class="mt-0.5 break-words text-[10px] text-slate-400 line-clamp-1 font-semibold">{{ $recom->penulis }}</p>
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
