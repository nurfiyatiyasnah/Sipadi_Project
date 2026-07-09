<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pengumuman->judul }} - SIPADI Bukittinggi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f5e9] font-sans text-[#061b3a] antialiased">

    <!-- Header / Navbar -->
    @include('layouts.public_navbar')

    <!-- Breadcrumb Area -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 pt-10">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('landing') }}" class="hover:text-[#04241e] transition">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <a href="{{ route('pengumuman.public.index') }}" class="hover:text-[#04241e] transition">Pengumuman</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="text-slate-700 truncate max-w-[200px] sm:max-w-xs md:max-w-md">{{ $pengumuman->judul }}</span>
        </nav>
    </div>

    <!-- Main Content Area -->
    <div class="mx-auto grid max-w-7xl grid-cols-1 gap-8 px-6 py-8 lg:grid-cols-[1fr_320px] lg:px-12">
        
        <!-- Left Column: Announcement Detail -->
        <main class="min-w-0">
            <article class="min-w-0 space-y-6 overflow-hidden rounded-3xl border border-slate-100 bg-white p-6 shadow-sm lg:p-10">
                
                <!-- Meta Info & Title -->
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
                        <span class="bg-[#04241e] text-[#ffdc7c] text-[10px] font-bold px-3 py-1 rounded-md uppercase tracking-wider">
                            Pengumuman
                        </span>
                        <span class="flex items-center gap-1.5">
                            <i class="fa-regular fa-calendar"></i>
                            {{ $pengumuman->tanggal_mulai->locale('id')->translatedFormat('d F Y') }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <i class="fa-regular fa-eye"></i>
                            Dilihat {{ $pengumuman->total_views }} kali
                        </span>
                    </div>
                    
                    <h1 class="break-words font-serif text-3xl lg:text-4xl font-bold leading-tight text-[#04241e]">
                        {{ $pengumuman->judul }}
                    </h1>

                    <!-- Author Info -->
                    <div class="flex items-center gap-3 pt-2">
                        <div class="h-10 w-10 rounded-full bg-emerald-50 text-[#04241e] flex items-center justify-center font-bold text-sm border border-emerald-100">
                            DP
                        </div>
                        <div class="leading-tight">
                            <span class="block text-xs font-bold text-[#04241e]">Dinas Perpustakaan</span>
                            <span class="block text-[10px] text-slate-400 mt-0.5">Admin SIPADI</span>
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="min-w-0 overflow-hidden rounded-3xl w-full h-[320px] md:h-[400px]">
                    @if($pengumuman->gambar)
                        <img src="{{ Storage::url($pengumuman->gambar) }}" alt="{{ $pengumuman->judul }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('images/default-pengumuman.png') }}" alt="{{ $pengumuman->judul }}" class="w-full h-full object-cover">
                    @endif
                </div>

                <!-- Body Content -->
                <div class="break-all text-[#061b3a] text-sm lg:text-base leading-relaxed space-y-4 pt-6 border-t border-slate-100/60 font-sans">
                    @if(strip_tags($pengumuman->isi) !== $pengumuman->isi)
                        {!! $pengumuman->isi !!}
                    @else
                        {!! nl2br(e($pengumuman->isi)) !!}
                    @endif
                </div>

                <!-- Key Schedule Info Box -->
                <div class="min-w-0 rounded-2xl border border-sky-100 bg-sky-50/50 p-6 space-y-4">
                    <div class="flex items-center gap-3 text-sky-800 font-bold text-sm">
                        <i class="fa-solid fa-circle-info text-base"></i>
                        <span>Informasi Penting Terkait Pengumuman</span>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2 text-xs">
                        <div class="space-y-1">
                            <span class="block text-slate-400 font-semibold uppercase tracking-wider">Tanggal Mulai Berlaku</span>
                            <span class="block font-bold text-slate-700">
                                {{ $pengumuman->tanggal_mulai->locale('id')->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        <div class="space-y-1">
                            <span class="block text-slate-400 font-semibold uppercase tracking-wider">Tanggal Berakhir</span>
                            <span class="block font-bold text-slate-700">
                                {{ $pengumuman->tanggal_selesai->locale('id')->translatedFormat('d F Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Attachments (Lampiran) -->
                @if($pengumuman->file_lampiran && count($pengumuman->file_lampiran) > 0)
                    <div class="pt-6 border-t border-slate-100 space-y-3">
                        <h4 class="font-bold text-sm text-[#04241e] uppercase tracking-wider">Lampiran</h4>
                        <div class="space-y-2.5">
                            @foreach($pengumuman->file_lampiran as $item)
                                <div class="flex min-w-0 items-center justify-between gap-4 p-4 rounded-2xl border border-slate-100 bg-slate-50">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-slate-200/60 text-red-500 text-lg flex-shrink-0">
                                            <i class="fa-regular fa-file-pdf"></i>
                                        </span>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-slate-700 truncate" title="{{ $item['name'] }}">{{ $item['name'] }}</p>
                                            <p class="text-[10px] font-semibold text-slate-400 mt-0.5">{{ $item['size'] ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($item['path']) }}" download class="flex h-8 w-8 items-center justify-center rounded-full bg-[#04241e] text-white hover:bg-[#ffdc7c] hover:text-[#04241e] transition flex-shrink-0 text-sm">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Share Box -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-400">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-share-nodes"></i>
                        Bagikan:
                    </span>
                    <div class="flex items-center gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-blue-500 hover:text-white transition">
                            <i class="fa-brands fa-facebook-f text-xs"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($pengumuman->judul) }}" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-sky-400 hover:text-white transition">
                            <i class="fa-brands fa-twitter text-xs"></i>
                        </a>
                        <button onclick="navigator.clipboard.writeText('{{ request()->url() }}'); alert('Link berhasil disalin!')" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-emerald-500 hover:text-white transition">
                            <i class="fa-solid fa-link text-xs"></i>
                        </button>
                    </div>
                </div>

            </article>
        </main>

        <!-- Right Column: Sidebar -->
        <aside class="min-w-0 space-y-6">
            
            <!-- Butuh Bantuan Widget -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                <h3 class="font-serif font-bold text-lg text-[#04241e]">Butuh Bantuan?</h3>
                <p class="text-slate-500 text-xs leading-relaxed">
                    Jika Anda memiliki pertanyaan mendesak terkait layanan perpustakaan digital SIPADI, silakan hubungi kontak bantuan kami.
                </p>
                <a href="{{ route('landing') }}#kontak" class="flex h-11 w-full items-center justify-center rounded-xl bg-[#04241e] hover:bg-[#ffdc7c] hover:text-[#04241e] font-bold text-white text-xs transition shadow-sm">
                    <i class="fa-regular fa-envelope mr-2"></i> Hubungi Layanan Bantuan
                </a>
            </div>

            <!-- Pengumuman Lainnya Widget -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                <h3 class="font-serif font-bold text-lg text-[#04241e] border-b border-slate-100 pb-2">Pengumuman Lainnya</h3>
                <div class="space-y-4">
                    @forelse($recentPengumuman as $recent)
                        <a href="{{ route('pengumuman.public.show', $recent->slug) }}" class="block min-w-0 group transition">
                            <span class="text-[10px] font-bold text-slate-400">
                                {{ $recent->tanggal_mulai->locale('id')->translatedFormat('d F Y') }}
                            </span>
                            <h4 class="break-words font-bold text-xs text-[#061b3a] group-hover:text-emerald-800 transition line-clamp-2 mt-1">
                                {{ $recent->judul }}
                            </h4>
                        </a>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-2">Tidak ada pengumuman lainnya.</p>
                    @endforelse
                </div>
                @if($recentPengumuman->isNotEmpty())
                    <div class="border-t border-slate-100 pt-3">
                        <a href="{{ route('pengumuman.public.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#04241e] hover:text-[#ffdc7c] transition">
                            Lihat Semua Pengumuman
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                @endif
            </div>

        </aside>
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
