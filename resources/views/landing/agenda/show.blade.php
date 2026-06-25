@extends('layouts.public')
 
@section('title', $agenda->judul_event . ' - SIPADI Bukittinggi')
 
@section('content')
<div class="mx-auto max-w-7xl px-6 py-12 lg:px-12 space-y-8">
    
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('landing') }}" class="hover:text-slate-800 transition">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <a href="{{ route('agenda.index') }}" class="hover:text-slate-800 transition">Agenda</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="font-medium text-slate-800 truncate">{{ $agenda->judul_event }}</span>
    </nav>
 
    {{-- Event Banner Card --}}
    <div class="relative bg-[#04241e] text-white rounded-[2.5rem] p-8 md:p-12 overflow-hidden min-h-[300px] flex flex-col justify-end shadow-lg group">
        {{-- Background Image --}}
        @if($agenda->gambar)
            <img src="{{ Storage::url($agenda->gambar) }}" alt="{{ $agenda->judul_event }}" class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:scale-[1.01] transition duration-700">
        @else
            <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=1200&q=80" alt="Banner fallback" class="absolute inset-0 w-full h-full object-cover opacity-10">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
 
        <div class="relative z-10 space-y-4 max-w-4xl">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="rounded-full bg-white/20 backdrop-blur-sm px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-[#ffdc7c]">
                    {{ $agenda->kategori ?? 'Kegiatan' }}
                </span>
                <span class="rounded-full bg-white/10 backdrop-blur-sm px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-slate-200">
                    Terbuka untuk Umum
                </span>
            </div>
            
            <h1 class="font-serif text-3xl md:text-4xl lg:text-5xl font-bold leading-tight text-white">
                {{ $agenda->judul_event }}
            </h1>
            
            <p class="text-sm md:text-base text-slate-300 max-w-2xl leading-relaxed font-light">
                {{ Str::limit(strip_tags($agenda->deskripsi), 160) }}
            </p>
        </div>
    </div>
 
    {{-- Content Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left Side: Main Event Information --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm space-y-8">
                
                {{-- Tentang Acara --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2.5">
                        <span class="h-1.5 w-6 rounded-full bg-[#04241e]"></span>
                        Tentang Acara
                    </h3>
                    <div class="text-slate-600 text-sm md:text-base leading-relaxed whitespace-pre-line">
                        {{ $agenda->deskripsi }}
                    </div>
                </div>
 
                {{-- Narasumber --}}
                <div class="space-y-4 pt-6 border-t border-slate-100">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2.5">
                        <span class="h-1.5 w-6 rounded-full bg-[#04241e]"></span>
                        Narasumber
                    </h3>
                    
                    <div class="grid gap-4 sm:grid-cols-2">
                        {{-- Speaker 1 --}}
                        <div class="bg-blue-50/40 rounded-2xl p-4 border border-blue-100/50 flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=100" alt="Speaker" class="h-14 w-14 rounded-full object-cover shadow-sm">
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 leading-snug">Prof. Dr. Irwandi, M.Hum.</h4>
                                <p class="text-[11px] text-slate-400 mt-0.5">Sejarawan Universitas Negeri Andalas</p>
                            </div>
                        </div>
                        {{-- Speaker 2 --}}
                        <div class="bg-blue-50/40 rounded-2xl p-4 border border-blue-100/50 flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&q=80&w=100" alt="Speaker" class="h-14 w-14 rounded-full object-cover shadow-sm">
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 leading-snug">Bundo Elly Kasim</h4>
                                <p class="text-[11px] text-slate-400 mt-0.5">Budayawan & Pemerhati Budaya Minangkabau</p>
                            </div>
                        </div>
                    </div>
                </div>
 
                {{-- Rangkaian Acara --}}
                <div class="space-y-4 pt-6 border-t border-slate-100">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2.5">
                        <span class="h-1.5 w-6 rounded-full bg-[#04241e]"></span>
                        Rangkaian Acara
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-start gap-3 text-sm text-slate-600">
                            <span class="text-emerald-500 mt-0.5"><i class="fa-solid fa-circle-check text-lg"></i></span>
                            <span class="font-medium">Pembukaan dan sambutan hangat dari kepala Dinas Perpustakaan dan Kearsipan Kota Bukittinggi.</span>
                        </div>
                        <div class="flex items-start gap-3 text-sm text-slate-600">
                            <span class="text-emerald-500 mt-0.5"><i class="fa-solid fa-circle-check text-lg"></i></span>
                            <span class="font-medium">Sesi pemaparan materi utama, pembahasan sejarah penulisan, and konteks sosial budaya oleh narasumber.</span>
                        </div>
                        <div class="flex items-start gap-3 text-sm text-slate-600">
                            <span class="text-emerald-500 mt-0.5"><i class="fa-solid fa-circle-check text-lg"></i></span>
                            <span class="font-medium">Sesi tanya jawab interaktif dan diskusi terbuka bersama dengan seluruh peserta.</span>
                        </div>
                        <div class="flex items-start gap-3 text-sm text-slate-600">
                            <span class="text-emerald-500 mt-0.5"><i class="fa-solid fa-circle-check text-lg"></i></span>
                            <span class="font-medium">Pembagian sertifikat elektronik, sesi dokumentasi foto bersama, and ramah tamah penutup.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 
        {{-- Right Side: Registration Info & Related Books --}}
        <div class="space-y-6">
            
            {{-- Info Box / Registration Card --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm space-y-6">
                
                <div class="space-y-5">
                    {{-- Waktu --}}
                    <div class="flex gap-4 items-start">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-500 text-lg-shrink-0 mt-0.5">
                            <i class="fa-regular fa-calendar"></i>
                        </span>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Waktu Pelaksanaan</p>
                            <p class="text-sm font-bold text-slate-800 mt-1 leading-normal">
                                {{ $agenda->tanggal_mulai ? $agenda->tanggal_mulai->locale('id')->translatedFormat('l, d F Y') : '-' }}
                            </p>
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ $agenda->jam_mulai ? substr($agenda->jam_mulai, 0, 5) : '--:--' }} 
                                @if($agenda->jam_selesai)
                                    - {{ substr($agenda->jam_selesai, 0, 5) }}
                                @endif
                                WIB
                            </p>
                        </div>
                    </div>
 
                    {{-- Lokasi --}}
                    @php
                        $isUrl = filter_var($agenda->lokasi, FILTER_VALIDATE_URL);
                        $mapUrl = $isUrl ? $agenda->lokasi : "https://maps.google.com/?q=" . urlencode($agenda->lokasi ?? 'Perpustakaan Bukittinggi');
                    @endphp
                    <div class="flex gap-4 items-start border-t border-slate-50 pt-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-500 text-lg-shrink-0 mt-0.5">
                            <i class="fa-solid fa-location-dot"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Lokasi</p>
                            <p class="text-sm font-bold text-slate-800 mt-1 leading-normal truncate">
                                @if($isUrl)
                                    Tautan Google Maps
                                @else
                                    {{ $agenda->lokasi ?? '-' }}
                                @endif
                            </p>
                            @if(!$isUrl && $agenda->lokasi)
                                <p class="text-xs text-slate-400 mt-0.5 truncate">Dinas Perpustakaan Daerah Bukittinggi</p>
                            @endif
                            <a href="{{ $mapUrl }}" target="_blank" class="text-xs font-bold text-[#8c741c] hover:text-[#725e17] mt-2.5 inline-flex items-center gap-1.5 hover:underline">
                                <i class="fa-solid fa-map-location-dot text-sm"></i> Lihat Peta →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
 
            {{-- Koleksi Terkait Card --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Koleksi Terkait</h3>
                
                <div class="space-y-4">
                    @forelse($relatedBooks as $book)
                        <div class="flex gap-4 items-center border-b border-slate-50 pb-3 last:border-b-0 last:pb-0">
                            {{-- Mock book cover style --}}
                            <div class="h-16 w-12 rounded-lg bg-emerald-800 text-white flex flex-col justify-center items-center p-2 text-center shadow-sm overflow-hidden flex-shrink-0 relative">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-black/20"></div>
                                <span class="font-serif font-extrabold text-[7px] leading-tight line-clamp-2">
                                    {{ $book->judul }}
                                </span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-800 truncate">
                                    {{ $book->judul }}
                                </h4>
                                <p class="text-[10px] text-slate-400 mt-0.5 truncate">
                                    {{ $book->penulis }}
                                </p>
                                <span class="inline-block text-[9px] font-semibold text-slate-500 bg-slate-100 rounded px-1.5 py-0.5 mt-1">
                                    {{ $book->kategori?->nama_kategori ?? 'Umum' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">Tidak ada koleksi terkait saat ini.</p>
                    @endforelse
                </div>
            </div>
 
            {{-- Agenda Lainnya --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Agenda Terkait</h3>
                <div class="space-y-4">
                    @forelse($relatedEvents as $rel)
                        <a href="{{ route('agenda.show', $rel->slug) }}" class="flex items-center gap-3.5 group">
                            <div class="flex flex-col items-center justify-center h-12 w-12 rounded-xl bg-slate-50 border border-slate-100 group-hover:bg-amber-50 group-hover:border-amber-200 transition flex-shrink-0">
                                <span class="text-[9px] font-bold text-slate-400 uppercase">
                                    {{ $rel->tanggal_mulai ? $rel->tanggal_mulai->locale('id')->translatedFormat('M') : 'AGEN' }}
                                </span>
                                <span class="text-sm font-extrabold text-slate-700">
                                    {{ $rel->tanggal_mulai ? $rel->tanggal_mulai->format('d') : '00' }}
                                </span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-800 truncate group-hover:text-[#04241e] transition">
                                    {{ $rel->judul_event }}
                                </h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">
                                    {{ $rel->jam_mulai ? substr($rel->jam_mulai, 0, 5) : '--:--' }} WIB • 
                                    @if(filter_var($rel->lokasi, FILTER_VALIDATE_URL))
                                        Link Peta
                                    @else
                                        {{ Str::limit($rel->lokasi, 16) }}
                                    @endif
                                </p>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-slate-400">Tidak ada agenda lain saat ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
