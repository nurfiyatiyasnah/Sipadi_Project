@extends('layouts.petugas')
 
@section('title', 'Detail Agenda')
 
@section('content')
<div class="mx-auto max-w-[1180px] space-y-6">
    {{-- Breadcrumbs & Action Buttons --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-800 transition">SIPADI</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <a href="{{ route('petugas.agenda.index') }}" class="hover:text-slate-800 transition">Agenda</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="font-medium text-slate-800">Detail Agenda</span>
        </nav>
        <div class="flex items-center gap-3">
            <a href="{{ route('petugas.agenda.edit', $agenda) }}"
               class="inline-flex items-center gap-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition">
                <i class="fa-regular fa-pen-to-square"></i>
                Edit Agenda
            </a>
            <button class="inline-flex items-center gap-2 rounded-xl bg-[#0e1f30] hover:bg-[#1a2f44] px-4 py-2.5 text-sm font-bold text-white shadow-sm transition">
                <i class="fa-solid fa-share-nodes"></i>
                Bagikan
            </button>
        </div>
    </div>
 
    {{-- Main Grid Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Side: Event Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-sm">
                
                {{-- Banner image --}}
                <div class="relative h-64 md:h-80 bg-slate-900 overflow-hidden">
                    @if($agenda->gambar)
                        <img src="{{ Storage::url($agenda->gambar) }}" alt="{{ $agenda->judul_event }}" class="w-full h-full object-cover">
                    @else
                        {{-- Nice abstract CSS gradient banner --}}
                        <div class="w-full h-full bg-gradient-to-br from-[#142b3d] via-[#1a3d58] to-[#0e1f30] flex flex-col justify-end p-8">
                            <span class="inline-flex max-w-fit rounded-lg bg-amber-400/20 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-amber-400">
                                {{ strtoupper($agenda->kategori ?? 'AGENDA') }}
                            </span>
                            <h1 class="mt-4 font-serif text-2xl md:text-3xl font-bold text-white leading-tight">
                                {{ $agenda->judul_event }}
                            </h1>
                        </div>
                    @endif
                    
                    {{-- Overlay Category Badge (only if image exists) --}}
                    @if($agenda->gambar)
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex flex-col justify-end p-8">
                            <span class="inline-flex max-w-fit rounded-lg bg-amber-400 px-3 py-1 text-xs font-bold uppercase tracking-wider text-slate-950">
                                {{ $agenda->kategori ?? 'AGENDA' }}
                            </span>
                            <h1 class="mt-3 font-serif text-xl md:text-2xl font-bold text-white leading-tight">
                                {{ $agenda->judul_event }}
                            </h1>
                        </div>
                    @endif
                </div>
 
                {{-- Info badge row --}}
                <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-100 border-b border-slate-100">
                    {{-- Tanggal --}}
                    <div class="p-6 flex items-start gap-3">
                        <span class="text-xl text-slate-400 mt-1"><i class="fa-regular fa-calendar"></i></span>
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tanggal</div>
                            <div class="text-sm font-bold text-slate-800 mt-1">
                                {{ $agenda->tanggal_mulai ? $agenda->tanggal_mulai->locale('id')->translatedFormat('l, d F Y') : '-' }}
                            </div>
                        </div>
                    </div>
                    {{-- Waktu --}}
                    <div class="p-6 flex items-start gap-3">
                        <span class="text-xl text-slate-400 mt-1"><i class="fa-regular fa-clock"></i></span>
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Waktu</div>
                            <div class="text-sm font-bold text-slate-800 mt-1">
                                {{ $agenda->jam_mulai ? substr($agenda->jam_mulai, 0, 5) : '--:--' }} 
                                @if($agenda->jam_selesai)
                                    - {{ substr($agenda->jam_selesai, 0, 5) }}
                                @else
                                    - Selesai
                                @endif
                                WIB
                            </div>
                        </div>
                    </div>
                    {{-- Lokasi --}}
                    <div class="p-6 flex items-start gap-3">
                        <span class="text-xl text-slate-400 mt-1"><i class="fa-solid fa-location-dot"></i></span>
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Lokasi</div>
                            <div class="text-sm font-bold text-slate-800 mt-1">
                                @if(filter_var($agenda->lokasi, FILTER_VALIDATE_URL))
                                    <a href="{{ $agenda->lokasi }}" target="_blank" class="text-[#8c741c] hover:underline flex items-center gap-1">
                                        Buka Tautan Peta <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </a>
                                @else
                                    {{ $agenda->lokasi ?? '-' }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
 
                {{-- Description section --}}
                <div class="p-8 space-y-4">
                    <h3 class="text-lg font-bold text-slate-800">Deskripsi Acara</h3>
                    <div class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">
                        {{ $agenda->deskripsi }}
                    </div>
                </div>
            </div>
 
            {{-- Peta Lokasi Card --}}
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800">Peta Lokasi</h3>
                    @php
                        $isUrl = filter_var($agenda->lokasi, FILTER_VALIDATE_URL);
                        $mapUrl = $isUrl ? $agenda->lokasi : "https://maps.google.com/?q=" . urlencode($agenda->lokasi ?? 'Perpustakaan Bukittinggi');
                    @endphp
                    <a href="{{ $mapUrl }}" target="_blank"
                       class="text-xs font-bold text-[#8c741c] hover:underline flex items-center gap-1.5">
                        Buka di Google Maps <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
                
                {{-- Styled mock map graphic --}}
                <div class="relative h-60 rounded-2xl bg-slate-50 border border-slate-100 overflow-hidden flex items-center justify-center">
                    {{-- Abstract SVG representation of a map grid with library building --}}
                    <svg class="absolute inset-0 w-full h-full text-slate-200" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" preserveAspectRatio="none">
                        <defs>
                            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="1"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid)" />
                        <!-- Road paths -->
                        <path d="M-50,120 L600,120" stroke="#fff" stroke-width="24" fill="none" />
                        <path d="M250,-50 L250,300" stroke="#fff" stroke-width="24" fill="none" />
                        <!-- Building block -->
                        <rect x="80" y="30" width="120" height="70" rx="12" fill="#c4b5fd" opacity="0.7" />
                        <rect x="290" y="150" width="150" height="80" rx="16" fill="#a7f3d0" opacity="0.7" />
                    </svg>
                    
                    {{-- Map marker --}}
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="animate-bounce flex items-center justify-center h-12 w-12 rounded-full bg-amber-500 text-white shadow-md border-2 border-white">
                            <i class="fa-solid fa-location-crosshairs text-lg"></i>
                        </div>
                        <div class="mt-2 bg-slate-900/90 text-white text-xs font-bold rounded-lg px-3 py-1.5 shadow-md max-w-[200px] truncate">
                            {{ $isUrl ? 'Tautan Google Maps' : ($agenda->lokasi ?? 'Lokasi Acara') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
 
        {{-- Right Side: Sidebar Cards --}}
        <div class="space-y-6">
            
            {{-- Status & Capacity --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-6">
                <div class="flex items-center justify-between">
                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
                        @if ($agenda->status_event === 'terbit')
                            Aktif
                        @elseif ($agenda->status_event === 'draft')
                            Draft
                        @else
                            Menunggu Review
                        @endif
                    </span>
                    {{-- Dummy avatars --}}
                    <div class="flex -space-x-2">
                        <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=40" alt="Avatar">
                        <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=40" alt="Avatar">
                        <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&q=80&w=40" alt="Avatar">
                        <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-slate-100 ring-2 ring-white text-[9px] font-bold text-slate-500">+12</span>
                    </div>
                </div>
 
                {{-- Capacity indicator --}}
                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-bold text-slate-700">
                        <span>Kapasitas Peserta</span>
                        <span>45 / 50</span>
                    </div>
                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: 90%"></div>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">
                        Tersisa 5 slot pendaftaran untuk acara ini.
                    </p>
                </div>
 
                <button class="w-full py-3.5 bg-[#8c741c] hover:bg-[#725e17] text-white font-bold text-sm rounded-xl transition shadow-sm">
                    Daftarkan Peserta Manual
                </button>
            </div>
 
            {{-- Narasumber Utama --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Narasumber Utama</h3>
                <div class="flex items-start gap-4">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=100" alt="Speaker" class="h-12 w-12 rounded-xl object-cover shadow-sm">
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">Dr. Elara Vance, Ph.D.</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Pakar Literasi Digital & AI Ethics</p>
                    </div>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed pt-2">
                    Dosen tamu dari Universitas Teknologi Indonesia, penulis buku "Human-Centric AI".
                </p>
            </div>
 
            {{-- Agenda Terkait --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Agenda Terkait</h3>
                
                <div class="space-y-4">
                    @forelse($relatedEvents as $rel)
                        <a href="{{ route('petugas.agenda.show', $rel) }}" class="flex items-center gap-3.5 group">
                            {{-- Date block --}}
                            <div class="flex flex-col items-center justify-center h-12 w-12 rounded-xl bg-slate-50 border border-slate-100 group-hover:bg-amber-50 group-hover:border-amber-200 transition">
                                <span class="text-[9px] font-bold text-slate-400 uppercase">
                                    {{ $rel->tanggal_mulai ? $rel->tanggal_mulai->locale('id')->translatedFormat('M') : 'AGEN' }}
                                </span>
                                <span class="text-sm font-extrabold text-slate-700">
                                    {{ $rel->tanggal_mulai ? $rel->tanggal_mulai->format('d') : '00' }}
                                </span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-800 truncate group-hover:text-slate-900">
                                    {{ $rel->judul_event }}
                                </h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">
                                    {{ $rel->jam_mulai ? substr($rel->jam_mulai, 0, 5) : '--:--' }} WIB • {{ Str::limit($rel->lokasi, 16) }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-slate-400">Tidak ada agenda lain saat ini.</p>
                    @endforelse
                </div>
 
                <a href="{{ route('petugas.agenda.index') }}"
                   class="flex h-11 w-full items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 text-xs font-bold text-slate-600 transition mt-4">
                    Lihat Semua Agenda
                </a>
            </div>
 
            {{-- Informasi Sistem --}}
            <div class="text-[11px] text-slate-400 space-y-1.5 px-2">
                <div>
                    <i class="fa-solid fa-circle-info mr-1"></i> Informasi Sistem
                </div>
                <div>
                    Dibuat: {{ $agenda->created_at ? $agenda->created_at->locale('id')->translatedFormat('d M Y') : '-' }} oleh {{ $agenda->createdBy?->nama_petugas ?? 'Admin' }}
                </div>
                <div>
                    Terakhir Update: {{ $agenda->updated_at ? $agenda->updated_at->locale('id')->translatedFormat('d M Y') : '-' }}
                </div>
            </div>
 
        </div>
    </div>
</div>
@endsection
