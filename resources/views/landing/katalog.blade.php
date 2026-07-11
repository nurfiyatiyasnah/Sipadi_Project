<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku Digital - SIPADI Bukittinggi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f5e9] font-sans text-[#061b3a] antialiased">

    <!-- Header / Navbar -->
    @include('layouts.public_navbar')

    <!-- Page Title Area -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 py-8">
        <h1 class="font-serif text-4xl font-bold text-[#04241e]">Katalog Buku Digital</h1>
    </div>

    <!-- Main Container -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 pb-16">
        <form id="filterForm" action="{{ route('katalog') }}" method="GET">
            <!-- Search & Sort Row -->
            <div class="flex flex-col md:flex-row items-center gap-4 bg-white p-4 rounded-3xl border border-slate-100 shadow-sm mb-6">
                <!-- Search Input -->
                <div class="relative flex-1 w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul buku, penulis, atau ISBN..." 
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 py-3.5 pl-5 pr-12 text-sm text-[#061b3a] focus:border-[#04241e] focus:ring-0 focus:outline-none placeholder:text-slate-400">
                    <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#04241e] transition">
                        <i class="fa-solid fa-magnifying-glass text-base"></i>
                    </button>
                </div>

                <!-- Sort Dropdown -->
                <div class="flex items-center gap-3 w-full md:w-auto shrink-0 border-t md:border-t-0 pt-3 md:pt-0">
                    <span class="text-sm font-semibold text-slate-500 whitespace-nowrap">Urutkan:</span>
                    <select name="sort" onchange="this.form.submit()" 
                            class="rounded-xl border border-slate-200 bg-white py-2.5 pl-4 pr-10 text-sm font-semibold text-[#061b3a] focus:border-[#04241e] focus:ring-0 focus:outline-none cursor-pointer">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                        <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>A-Z</option>
                        <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Z-A</option>
                    </select>
                </div>
            </div>

            <!-- Content Area: Left Sidebar, Right Grid -->
            <div class="grid gap-8 lg:grid-cols-[300px_1fr]">
                <!-- Sidebar Filter -->
                <aside class="space-y-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col gap-6">
                        <!-- Filter Header -->
                        <div class="flex items-center justify-between border-b pb-4">
                            <h3 class="font-serif font-bold text-xl text-[#04241e] flex items-center gap-2">
                                <i class="fa-solid fa-sliders text-sm"></i>
                                Filter
                            </h3>
                            <button type="button" onclick="resetAllFilters()" class="text-xs font-bold text-red-600 hover:underline">
                                Reset Filter
                            </button>
                        </div>

                        <!-- Kategori Filter -->
                        <div>
                            <h4 class="font-bold text-sm text-[#061b3a] mb-3">Kategori</h4>
                            <div class="space-y-3">
                                @foreach($categories as $category)
                                    <label class="flex items-center gap-3 text-sm text-slate-600 hover:text-[#04241e] cursor-pointer">
                                        <input type="checkbox" name="kategori[]" value="{{ $category->id_kategori }}" 
                                               onchange="this.form.submit()"
                                               {{ is_array(request('kategori')) && in_array($category->id_kategori, request('kategori')) ? 'checked' : '' }}
                                               class="rounded border-slate-300 text-[#04241e] focus:ring-0">
                                        <span>{{ $category->nama_kategori }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="border-t pt-4">
                            <h4 class="font-bold text-sm text-[#061b3a] mb-3">Status</h4>
                            <div class="space-y-3">
                                <label class="flex items-center gap-3 text-sm text-slate-600 hover:text-[#04241e] cursor-pointer">
                                    <input type="radio" name="status" value="" 
                                           onchange="this.form.submit()"
                                           {{ !request('status') ? 'checked' : '' }}
                                           class="text-[#04241e] focus:ring-0 border-slate-300">
                                    <span>Semua</span>
                                </label>
                                <label class="flex items-center gap-3 text-sm text-slate-600 hover:text-[#04241e] cursor-pointer">
                                    <input type="radio" name="status" value="tersedia" 
                                           onchange="this.form.submit()"
                                           {{ request('status') === 'tersedia' ? 'checked' : '' }}
                                           class="text-[#04241e] focus:ring-0 border-slate-300">
                                    <span>Tersedia</span>
                                </label>
                                <label class="flex items-center gap-3 text-sm text-slate-600 hover:text-[#04241e] cursor-pointer">
                                    <input type="radio" name="status" value="dipinjam" 
                                           onchange="this.form.submit()"
                                           {{ request('status') === 'dipinjam' ? 'checked' : '' }}
                                           class="text-[#04241e] focus:ring-0 border-slate-300">
                                    <span>Sedang Dipinjam</span>
                                </label>
                            </div>
                        </div>

                        <!-- Tahun Terbit Filter -->
                        <div class="border-t pt-4">
                            <h4 class="font-bold text-sm text-[#061b3a] mb-3">Tahun Terbit</h4>
                            <div class="flex items-center gap-2">
                                <input type="number" name="tahun_dari" value="{{ request('tahun_dari') }}" placeholder="Dari" 
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-2.5 px-3.5 text-sm text-center focus:border-[#04241e] focus:ring-0 focus:outline-none placeholder:text-slate-400">
                                <span class="text-slate-400">-</span>
                                <input type="number" name="tahun_ke" value="{{ request('tahun_ke') }}" placeholder="Ke" 
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-2.5 px-3.5 text-sm text-center focus:border-[#04241e] focus:ring-0 focus:outline-none placeholder:text-slate-400">
                            </div>
                            <button type="submit" class="w-full mt-3 bg-[#04241e] hover:bg-[#073c32] text-white text-xs font-bold py-2.5 rounded-xl transition">
                                Terapkan Tahun
                            </button>
                        </div>
                    </div>
                </aside>

                <!-- Book Grid Area -->
                <main>
                    <!-- Active Tags Display -->
                    @if(request('kategori') || request('status') || request('tahun_dari') || request('tahun_ke') || request('search'))
                        <div class="flex flex-wrap gap-2 items-center mb-6">
                            @if(request('search'))
                                <span class="inline-flex items-center gap-1.5 bg-[#e2f0d9] text-[#2e6f40] px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                                    Cari: "{{ request('search') }}"
                                    <button type="button" onclick="clearSearch()" class="hover:text-red-600 transition">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </span>
                            @endif

                            @if(is_array(request('kategori')))
                                @foreach(request('kategori') as $selectedKatId)
                                    @php $katModel = $categories->firstWhere('id_kategori', $selectedKatId); @endphp
                                    @if($katModel)
                                        <span class="inline-flex items-center gap-1.5 bg-[#e2f0d9] text-[#2e6f40] px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                                            {{ $katModel->nama_kategori }}
                                            <button type="button" onclick="removeCategoryFilter({{ $selectedKatId }})" class="hover:text-red-600 transition">
                                                <i class="fa-solid fa-xmark text-[10px]"></i>
                                            </button>
                                        </span>
                                    @endif
                                @endforeach
                            @endif

                            @if(request('status'))
                                <span class="inline-flex items-center gap-1.5 bg-[#e2f0d9] text-[#2e6f40] px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                                    Status: {{ request('status') === 'tersedia' ? 'Tersedia' : 'Sedang Dipinjam' }}
                                    <button type="button" onclick="clearStatus()" class="hover:text-red-600 transition">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </span>
                            @endif

                            @if(request('tahun_dari') || request('tahun_ke'))
                                <span class="inline-flex items-center gap-1.5 bg-[#e2f0d9] text-[#2e6f40] px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                                    Tahun: {{ request('tahun_dari') ?? 'Mulai' }} - {{ request('tahun_ke') ?? 'Sekarang' }}
                                    <button type="button" onclick="clearYears()" class="hover:text-red-600 transition">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </span>
                            @endif
                        </div>
                    @endif

                    <!-- Grid of Cards -->
                    @if($books->isEmpty())
                        <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm">
                            <span class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-400 text-3xl mx-auto mb-4">
                                <i class="fa-solid fa-book-open"></i>
                            </span>
                            <h3 class="font-serif text-xl font-bold text-[#04241e]">Tidak ada buku ditemukan</h3>
                            <p class="text-slate-500 mt-2 text-sm">Coba ubah filter pencarian Anda.</p>
                        </div>
                    @else
                        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach($books as $book)
                                @php
                                    $availabilityStatus = $book->statusKetersediaan(false);
                                    $availabilityLabel = strtoupper($book->statusKetersediaanLabel(false));
                                    $availabilityBadgeClass = match ($availabilityStatus) {
                                        'tersedia' => 'bg-emerald-500/90 text-white',
                                        'dipinjam_semua' => 'bg-orange-500/90 text-white',
                                        'stok_kosong' => 'bg-slate-500/90 text-white',
                                        default => 'bg-rose-500/90 text-white',
                                    };
                                @endphp
                                <div class="group bg-white rounded-3xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:scale-[1.01] transition duration-200 flex flex-col justify-between">
                                    <a href="{{ route('katalog.show', $book->id_buku) }}" class="block">
                                        <!-- Styled Cover Wrap -->
                                        <div class="relative w-full h-[240px] rounded-2xl bg-slate-100 flex flex-col items-center justify-center shadow-sm overflow-hidden mb-4">
                                            <!-- Cover image background or custom placeholder -->
                                            @if($book->gambar_cover)
                                                @php
                                                    $imageUrl = str_starts_with($book->gambar_cover, 'http') ? $book->gambar_cover : asset('storage/' . $book->gambar_cover);
                                                @endphp
                                                <img src="{{ $imageUrl }}" alt="{{ $book->judul }}" class="w-full h-full object-cover">
                                            @else
                                                <!-- Dynamic Fallback Cover -->
                                                <div class="absolute inset-0 bg-gradient-to-br from-[#04241e] to-[#0a4b3f] p-5 text-white flex flex-col justify-between">
                                                    <div class="absolute left-0 top-0 bottom-0 w-3.5 bg-gradient-to-r from-black/20 to-transparent"></div>
                                                    <div class="mt-4 pl-3">
                                                        <h4 class="font-serif font-bold text-base leading-snug">{{ $book->judul }}</h4>
                                                    </div>
                                                    <p class="text-xs text-slate-300 pl-3">{{ $book->penulis }}</p>
                                                </div>
                                            @endif

                                            <!-- Status Badge -->
                                            <div class="absolute top-3 right-3 {{ $availabilityBadgeClass }} backdrop-blur-sm text-[9px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                                {{ $availabilityLabel }}
                                            </div>
                                        </div>

                                        <!-- Category tag, Title, Author -->
                                        <span class="text-[9px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 rounded px-2 py-0.5">
                                            {{ $book->kategori?->nama_kategori ?? 'Umum' }}
                                        </span>
                                        <h4 class="mt-2.5 font-bold text-[#061b3a] group-hover:text-[#04241e] transition line-clamp-2 leading-snug">
                                            {{ $book->judul }}
                                        </h4>
                                        <p class="text-xs text-slate-400 mt-1">{{ $book->penulis }}</p>
                                    </a>

                                    <!-- Bottom Info: Year -->
                                    <div class="flex items-center justify-between mt-4 border-t pt-3">
                                        <span class="text-xs font-semibold text-slate-400">{{ $book->tahun_terbit }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination Links -->
                        <div class="mt-12 flex justify-center">
                            {{ $books->links('pagination::tailwind') }}
                        </div>
                    @endif
                </main>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/60 py-12">
        <div class="mx-auto max-w-7xl px-6 lg:px-12 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h3 class="font-serif font-bold text-xl text-[#04241e]">SIPADI Bukittinggi</h3>
                <p class="text-slate-500 text-sm mt-2 max-w-md">Sistem Informasi Perpustakaan & Arsip Digital Kota Bukittinggi. Menghubungkan masyarakat dengan warisan budaya dan literasi terbaik.</p>
            </div>
            <div class="flex flex-wrap gap-6 text-sm font-semibold text-slate-600">
                <a href="{{ route('tentang') }}" class="hover:text-[#04241e] transition">Tentang Kami</a>
                <a href="#" class="hover:text-[#04241e] transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-[#04241e] transition">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-[#04241e] transition">Peta Situs</a>
            </div>
        </div>
        <div class="mx-auto max-w-7xl px-6 lg:px-12 mt-8 pt-6 border-t border-slate-100 text-center text-xs text-slate-400">
            &copy; 2026 Dinas Perpustakaan dan Kearsipan Kota Bukittinggi. Seluruh Hak Cipta Dilindungi.
        </div>
    </footer>

    <!-- Filter Actions JS Helpers -->
    <script>
        function removeCategoryFilter(id) {
            const checkboxes = document.querySelectorAll('input[name="kategori[]"]');
            checkboxes.forEach(cb => {
                if (cb.value == id) {
                    cb.checked = false;
                }
            });
            document.getElementById('filterForm').submit();
        }

        function clearSearch() {
            document.querySelector('input[name="search"]').value = '';
            document.getElementById('filterForm').submit();
        }

        function clearStatus() {
            const radios = document.querySelectorAll('input[name="status"]');
            radios.forEach(r => {
                if (r.value === '') {
                    r.checked = true;
                } else {
                    r.checked = false;
                }
            });
            document.getElementById('filterForm').submit();
        }

        function clearYears() {
            document.querySelector('input[name="tahun_dari"]').value = '';
            document.querySelector('input[name="tahun_ke"]').value = '';
            document.getElementById('filterForm').submit();
        }

        function resetAllFilters() {
            window.location.href = "{{ route('katalog') }}";
        }
    </script>

</body>
</html>
