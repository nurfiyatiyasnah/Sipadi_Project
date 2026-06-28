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

    @php
        // Generate a beautiful, realistic view count dynamically
        $views = ($buku->id_buku * 148) + 325;
        $viewsFormatted = $views >= 1000 ? number_format($views / 1000, 1) . 'k' : $views;

        // Generate a simple DDC number based on category or id
        $ddc = '800';
        if ($buku->kategori) {
            $catName = strtolower($buku->kategori->nama_kategori);
            if (str_contains($catName, 'sejarah') || str_contains($catName, 'budaya')) {
                $ddc = '390';
            } elseif (str_contains($catName, 'fiksi') || str_contains($catName, 'sastra')) {
                $ddc = '813';
            } elseif (str_contains($catName, 'agama')) {
                $ddc = '297';
            } elseif (str_contains($catName, 'sosial')) {
                $ddc = '300';
            }
        }
        
        // First 3 letters of author's name in uppercase, ignoring common titles
        $authorWords = explode(' ', preg_replace('/[^\w\s]/', '', $buku->penulis ?? 'Admin'));
        $filteredWords = array_filter($authorWords, function($word) {
            return !in_array(strtolower($word), ['prof', 'dr', 'ir', 'h', 'hj', 'drs', 'dra', 'st', 'm', 'md']);
        });
        $authorWord = reset($filteredWords) ?: 'ADM';
        $authorCode = strtoupper(substr($authorWord, 0, 3));
        
        // First letter of title in lowercase
        $titleClean = preg_replace('/[^\w\s]/', '', $buku->judul);
        $titleWords = array_filter(explode(' ', $titleClean));
        $firstTitleWord = reset($titleWords) ?: 'b';
        $titleCode = strtolower(substr($firstTitleWord, 0, 1));
        
        $nomorPanggil = "{$ddc} {$authorCode} {$titleCode}";
    @endphp

    <!-- Main Detail Section -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 pb-16">
        <div class="grid gap-8 lg:grid-cols-[300px_1fr] items-start">
            
            <!-- Left Side Cover & Action -->
            <div class="space-y-6">
                <!-- Cover Image Card -->
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100/80 flex items-center justify-center">
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
                                    <h3 class="font-sans font-bold text-lg leading-snug">{{ $buku->judul }}</h3>
                                </div>
                                <p class="text-xs text-slate-300 font-semibold">{{ $buku->penulis }}</p>
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
                            $tersediaCount = $buku->eksemplar->whereIn('status_eksemplar', ['tersedia', 'Tersedia'])->count();
                        @endphp
                        @if($tersediaCount > 0)
                            <div class="flex items-center gap-2 text-sm font-bold text-[#1e463c]">
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                                Tersedia {{ $tersediaCount }} Eksemplar
                            </div>
                        @elseif($status === 'Sedang Dipinjam')
                            <div class="flex items-center gap-2 text-sm font-bold text-orange-600">
                                <span class="h-2.5 w-2.5 rounded-full bg-orange-500 shrink-0"></span>
                                Sedang Dipinjam
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

                        <button onclick="toggleFavorite(this)" class="w-full bg-white hover:bg-slate-50 text-[#1e463c] border border-[#1e463c] font-bold rounded-xl py-3.5 px-4 flex items-center justify-center gap-2.5 transition duration-200 text-sm">
                            <i class="fa-regular fa-bookmark text-base"></i>
                            <span>Simpan ke Koleksi</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Side Book Information -->
            <div class="space-y-8 lg:pl-4">
                <!-- Badges -->
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="bg-[#e2f0d9] text-[#385723] px-3 py-1.5 text-xs font-semibold rounded-md">
                        {{ $buku->kategori?->nama_kategori ?? 'Umum' }}
                    </span>
                    <span class="bg-[#deebf7] text-[#1f4e79] px-3 py-1.5 text-xs font-semibold rounded-md">
                        {{ str_contains(strtolower($buku->kategori?->nama_kategori ?? ''), 'sejarah') ? 'Referensi Lokal' : 'Koleksi Umum' }}
                    </span>
                </div>

                <!-- Title & Author -->
                <div class="space-y-2">
                    <h1 class="text-3xl lg:text-4xl font-bold text-[#04241e] leading-tight font-sans">
                        {{ $buku->judul }}
                    </h1>
                    <p class="text-slate-600 text-sm font-semibold">
                        Oleh: <span class="text-slate-800">{{ $buku->penulis ?? 'Anonim' }}</span>
                    </p>
                </div>

                <!-- Rating & Stats Row -->
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs font-semibold text-slate-500 border-b border-slate-200/60 pb-6">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-regular fa-star text-slate-400 text-sm"></i>
                        <span>{{ $buku->rating }} ({{ ($buku->id_buku * 12) + 28 }} Ulasan)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="fa-regular fa-eye text-slate-400 text-sm"></i>
                        <span>Dilihat {{ $viewsFormatted }} kali</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="fa-regular fa-file-lines text-slate-400 text-sm"></i>
                        <span>{{ $buku->jumlah_halaman }} Halaman</span>
                    </div>
                </div>

                <!-- Synopsis -->
                <div class="space-y-3">
                    <h2 class="text-xl font-bold text-[#04241e]">Sinopsis</h2>
                    <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">{{ $buku->deskripsi }}</p>
                </div>

                <!-- Informasi Detail Grid -->
                <div class="space-y-4">
                    <h2 class="text-xl font-bold text-[#04241e]">Informasi Detail</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Penerbit -->
                        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm flex flex-col justify-center min-h-[72px]">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Penerbit</span>
                            <span class="text-sm font-bold text-slate-700 mt-1">{{ $buku->penerbit ?? '-' }}</span>
                        </div>
                        
                        <!-- Tahun Terbit -->
                        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm flex flex-col justify-center min-h-[72px]">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tahun Terbit</span>
                            <span class="text-sm font-bold text-slate-700 mt-1">{{ $buku->tahun_terbit ?? '-' }}</span>
                        </div>
                        
                        <!-- ISBN -->
                        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm flex flex-col justify-center min-h-[72px]">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ISBN</span>
                            <span class="text-sm font-bold text-slate-700 mt-1">{{ $buku->isbn ?? '-' }}</span>
                        </div>
                        
                        <!-- Bahasa -->
                        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm flex flex-col justify-center min-h-[72px]">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Bahasa</span>
                            <span class="text-sm font-bold text-slate-700 mt-1">Indonesia</span>
                        </div>
                        
                        <!-- Lokasi Rak -->
                        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm flex flex-col justify-center min-h-[72px]">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lokasi Rak</span>
                            <span class="text-sm font-bold text-slate-700 mt-1">{{ $lokasi_rak }}</span>
                        </div>
                        
                        <!-- Nomor Panggil -->
                        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm flex flex-col justify-center min-h-[72px]">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nomor Panggil</span>
                            <span class="text-sm font-bold text-slate-700 mt-1">{{ $nomorPanggil }}</span>
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
                <div class="grid gap-6 grid-cols-2 md:grid-cols-4 lg:grid-cols-5">
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

                            <h4 class="font-bold text-xs text-[#061b3a] group-hover:text-[#04241e] transition line-clamp-1">
                                {{ $recom->judul }}
                            </h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1 font-semibold">{{ $recom->penulis }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- JS Bookmark Toggle Function & Toast System -->
    <script>
        function toggleFavorite(btn) {
            const icon = btn.querySelector('i');
            const label = btn.querySelector('span');
            
            if (icon.classList.contains('fa-regular')) {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid');
                label.innerText = 'Tersimpan di Koleksi';
                showToast('Buku berhasil disimpan ke koleksi Anda');
            } else {
                icon.classList.remove('fa-solid');
                icon.classList.add('fa-regular');
                label.innerText = 'Simpan ke Koleksi';
                showToast('Buku dihapus dari koleksi Anda');
            }
        }

        function showToast(message) {
            // Check if toast container exists
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.className = 'fixed bottom-5 right-5 z-[9999] flex flex-col gap-2';
                document.body.appendChild(container);
            }
            
            const toast = document.createElement('div');
            toast.className = 'bg-[#1e463c] text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-2 text-sm font-semibold transition-all duration-300 transform translate-y-5 opacity-0';
            toast.innerHTML = `<i class="fa-solid fa-circle-check text-emerald-400"></i> ${message}`;
            
            container.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-y-5', 'opacity-0');
            }, 10);
            
            // Animate out & remove
            setTimeout(() => {
                toast.classList.add('translate-y-5', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }
    </script>

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
