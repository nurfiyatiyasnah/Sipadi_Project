<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fasilitas Perpustakaan - SIPADI Bukittinggi</title>

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
                <h1 class="font-serif text-4xl lg:text-5xl font-bold text-[#04241e] mt-2">Fasilitas Perpustakaan</h1>
                <p class="text-slate-500 mt-3 text-sm lg:text-base max-w-2xl">
                    Jelajahi berbagai fasilitas yang tersedia di Dinas Perpustakaan dan Kearsipan Kota Bukittinggi untuk menunjang kegiatan literasi dan aktivitas Anda.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 pb-16">
        
        <!-- Filter Tabs & Search -->
        <div class="mb-8 flex flex-col lg:flex-row gap-4 justify-between lg:items-center">
            <div class="flex gap-3 overflow-x-auto pb-2 lg:pb-0">
                <a href="{{ route('fasilitas.public.index') }}" class="whitespace-nowrap rounded-full px-5 py-2 text-sm font-semibold transition {{ $kategori === 'Semua' ? 'bg-[#04241e] text-white' : 'bg-white text-slate-600 hover:bg-slate-100 shadow-sm border border-slate-100' }}">Semua Kategori</a>
                <a href="{{ route('fasilitas.public.index', ['kategori' => 'Ruangan']) }}" class="whitespace-nowrap rounded-full px-5 py-2 text-sm font-semibold transition {{ $kategori === 'Ruangan' ? 'bg-[#04241e] text-white' : 'bg-white text-slate-600 hover:bg-slate-100 shadow-sm border border-slate-100' }}">Ruang Belajar & Diskusi</a>
                <a href="{{ route('fasilitas.public.index', ['kategori' => 'Elektronik']) }}" class="whitespace-nowrap rounded-full px-5 py-2 text-sm font-semibold transition {{ $kategori === 'Elektronik' ? 'bg-[#04241e] text-white' : 'bg-white text-slate-600 hover:bg-slate-100 shadow-sm border border-slate-100' }}">Perangkat IT</a>
                <a href="{{ route('fasilitas.public.index', ['kategori' => 'Peralatan']) }}" class="whitespace-nowrap rounded-full px-5 py-2 text-sm font-semibold transition {{ $kategori === 'Peralatan' ? 'bg-[#04241e] text-white' : 'bg-white text-slate-600 hover:bg-slate-100 shadow-sm border border-slate-100' }}">Peralatan Pendukung</a>
            </div>
            
            <form action="{{ route('fasilitas.public.index') }}" method="GET" class="relative min-w-[280px]">
                @if($kategori !== 'Semua')
                    <input type="hidden" name="kategori" value="{{ $kategori }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari fasilitas, lokasi..." class="w-full rounded-full border border-slate-200 bg-white py-2.5 pl-5 {{ request('search') ? 'pr-20' : 'pr-11' }} text-sm shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600 transition">
                
                @if(request('search'))
                    <a href="{{ route('fasilitas.public.index', $kategori !== 'Semua' ? ['kategori' => $kategori] : []) }}" class="absolute right-10 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center text-slate-400 hover:text-red-500 transition" title="Hapus pencarian">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </a>
                @endif

                <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 transition hover:bg-emerald-600 hover:text-white">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </form>
        </div>

        <!-- Fasilitas Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($fasilitas as $f)
                <a href="{{ route('fasilitas.public.show', $f->id_fasilitas) }}" class="group block rounded-3xl bg-white shadow-sm border border-slate-100 overflow-hidden hover:-translate-y-1 hover:shadow-lg transition duration-300 flex flex-col h-full">
                    <div class="relative h-56 w-full overflow-hidden bg-slate-100">
                        @if($f->gambar)
                            <img src="{{ asset('storage/' . $f->gambar) }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $f->nama_fasilitas }}">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-slate-300 bg-slate-100 transition duration-500 group-hover:scale-105">
                                <i class="fa-regular fa-image text-4xl"></i>
                            </div>
                        @endif
                        
                        <div class="absolute top-4 left-4 flex gap-2">
                            <span class="inline-flex items-center rounded-full bg-white/90 backdrop-blur px-2.5 py-1 text-xs font-bold text-[#04241e]">
                                {{ $f->kategori }}
                            </span>
                            @if(in_array(strtolower($f->status_fasilitas), ['tersedia', 'aktif']))
                                <span class="inline-flex items-center rounded-full bg-emerald-500/90 backdrop-blur px-2.5 py-1 text-xs font-bold text-white">
                                    Tersedia
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex flex-col flex-grow p-6">
                        <h3 class="font-serif text-xl font-bold text-[#04241e] group-hover:text-emerald-700 transition">{{ $f->nama_fasilitas }}</h3>
                        
                        <div class="mt-3 flex items-center gap-2 text-xs font-semibold text-slate-500">
                            <i class="fa-solid fa-location-dot text-emerald-600"></i>
                            <span>{{ $f->lokasi ?? 'Lokasi tidak disebutkan' }}</span>
                        </div>
                        
                        <p class="mt-4 text-sm text-slate-500 line-clamp-2 leading-relaxed">
                            {{ $f->deskripsi }}
                        </p>
                        
                        <div class="mt-auto pt-6 flex items-center justify-between border-t border-slate-100">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                                <i class="fa-solid fa-users text-slate-400"></i>
                                <span>Kapasitas: {{ $f->jumlah_unit ?? 0 }} {{ $f->satuan_kapasitas ?? 'Orang' }}</span>
                            </div>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 group-hover:bg-emerald-600 group-hover:text-white transition">
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-16 text-center text-slate-500">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-sm mb-4">
                        <i class="fa-regular fa-folder-open text-2xl text-slate-400"></i>
                    </div>
                    <p class="font-medium text-slate-600">Belum ada fasilitas untuk kategori ini.</p>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($fasilitas->hasPages())
        <div class="mt-10">
            {{ $fasilitas->links() }}
        </div>
        @endif

    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 mt-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
            <div class="flex flex-col sm:flex-row justify-between text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} Dinas Perpustakaan dan Kearsipan Kota Bukittinggi. Seluruh Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>
</body>
</html>
