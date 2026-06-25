@extends('layouts.public')
 
@section('title', 'Agenda Kegiatan - SIPADI Bukittinggi')
 
@section('content')
<div class="mx-auto max-w-7xl px-6 py-12 lg:px-12 space-y-10">
    
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('landing') }}" class="hover:text-slate-800 transition">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="font-medium text-slate-800">Agenda</span>
    </nav>
 
    {{-- Header Section --}}
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 border-b border-slate-200/60 pb-8">
        <div class="space-y-3">
            <h1 class="font-serif text-4xl lg:text-5xl font-bold text-[#061b3a]">Agenda Kegiatan</h1>
            <p class="text-slate-500 max-w-2xl text-base">
                Ikuti berbagai kegiatan, seminar, dan acara literasi yang diselenggarakan oleh Dinas Perpustakaan dan Kearsipan Kota Bukittinggi.
            </p>
        </div>
        
        {{-- Time Filters --}}
        <div class="flex flex-wrap gap-2">
            @php
                $currentFilter = request('filter', 'semua');
            @endphp
            <a href="{{ route('agenda.index', array_merge(request()->query(), ['filter' => 'semua'])) }}"
               class="rounded-xl px-4 py-2 text-xs font-bold transition border
               {{ $currentFilter === 'semua' ? 'bg-[#04241e] border-[#04241e] text-white' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                Semua
            </a>
            <a href="{{ route('agenda.index', array_merge(request()->query(), ['filter' => 'akan_datang'])) }}"
               class="rounded-xl px-4 py-2 text-xs font-bold transition border
               {{ $currentFilter === 'akan_datang' ? 'bg-[#04241e] border-[#04241e] text-white' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                Akan Datang
            </a>
            <a href="{{ route('agenda.index', array_merge(request()->query(), ['filter' => 'berlangsung'])) }}"
               class="rounded-xl px-4 py-2 text-xs font-bold transition border
               {{ $currentFilter === 'berlangsung' ? 'bg-[#04241e] border-[#04241e] text-white' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                Berlangsung
            </a>
            <a href="{{ route('agenda.index', array_merge(request()->query(), ['filter' => 'selesai'])) }}"
               class="rounded-xl px-4 py-2 text-xs font-bold transition border
               {{ $currentFilter === 'selesai' ? 'bg-[#04241e] border-[#04241e] text-white' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                Selesai
            </a>
        </div>
    </div>
 
    {{-- Search Form --}}
    <div class="flex justify-end">
        <form method="GET" action="{{ route('agenda.index') }}" class="w-full md:w-80">
            <input type="hidden" name="filter" value="{{ request('filter', 'semua') }}">
            <div class="relative">
                <span class="absolute inset-y-0 left-4 flex items-center text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari agenda..."
                       class="h-11 w-full rounded-2xl border border-slate-200 bg-white pl-11 pr-4 text-sm outline-none transition focus:border-[#04241e] placeholder:text-slate-400">
            </div>
        </form>
    </div>
 
    {{-- Agenda Grid --}}
    @if($events->isEmpty())
        <div class="bg-white rounded-[2rem] p-12 border border-slate-100 shadow-sm text-center space-y-4">
            <span class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-400 text-3xl">
                <i class="fa-regular fa-calendar-xmark"></i>
            </span>
            <h3 class="text-lg font-bold text-slate-700">Tidak ada agenda ditemukan</h3>
            <p class="text-slate-500 text-sm max-w-sm mx-auto">Coba ubah kata kunci pencarian Anda atau pilih filter waktu lainnya.</p>
        </div>
    @else
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($events as $event)
                @php
                    // Dynamic tag determination
                    $today = now()->toDateString();
                    $isAkanDatang = $event->tanggal_mulai ? $event->tanggal_mulai->toDateString() > $today : false;
                    
                    $isBerlangsung = false;
                    if ($event->tanggal_mulai) {
                        $start = $event->tanggal_mulai->toDateString();
                        $end = $event->tanggal_selesai ? $event->tanggal_selesai->toDateString() : $start;
                        $isBerlangsung = ($today >= $start && $today <= $end);
                    }
                    
                    $isSelesai = $event->tanggal_mulai ? (!$isAkanDatang && !$isBerlangsung) : false;
                    
                    $statusLabel = 'Kegiatan';
                    $statusClass = 'bg-blue-50 text-blue-700 border-blue-100';
                    
                    if ($isAkanDatang) {
                        $statusLabel = 'Akan Datang';
                        $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                    } elseif ($isBerlangsung) {
                        $statusLabel = 'Berlangsung';
                        $statusClass = 'bg-amber-50 text-amber-700 border-amber-100';
                    } elseif ($isSelesai) {
                        $statusLabel = 'Selesai';
                        $statusClass = 'bg-slate-100 text-slate-600 border-slate-200';
                    }
                @endphp
 
                <a href="{{ route('agenda.show', $event->slug) }}"
                   class="group bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm hover:shadow-md transition duration-300 flex flex-col">
                    
                    {{-- Banner image preview or fallback --}}
                    <div class="relative h-48 bg-slate-900 overflow-hidden flex-shrink-0">
                        @if($event->gambar)
                            <img src="{{ Storage::url($event->gambar) }}" alt="{{ $event->judul_event }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#04241e] to-[#12443c] flex items-center justify-center">
                                <i class="fa-solid fa-calendar-days text-4xl text-white/20"></i>
                            </div>
                        @endif
 
                        {{-- Featured tag (optional mockup) --}}
                        @if($event->tampilkan_beranda)
                            <span class="absolute top-4 left-4 rounded-full bg-[#ffdc7c] text-[#04241e] text-[10px] font-extrabold px-3 py-1 uppercase tracking-wider">
                                <i class="fa-solid fa-star mr-1"></i> Unggulan
                            </span>
                        @endif
                    </div>
 
                    {{-- Card body --}}
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-6">
                        <div class="space-y-3">
                            {{-- Badges --}}
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold border uppercase tracking-wider {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                                @if($event->kategori)
                                    <span class="rounded-full bg-slate-50 border border-slate-100 text-slate-500 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider">
                                        {{ $event->kategori }}
                                    </span>
                                @endif
                            </div>
 
                            {{-- Title --}}
                            <h3 class="font-serif text-lg font-bold text-slate-800 group-hover:text-[#04241e] transition line-clamp-2 leading-snug">
                                {{ $event->judul_event }}
                            </h3>
 
                            {{-- Desc --}}
                            <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                                {{ strip_tags($event->deskripsi) }}
                            </p>
                        </div>
 
                        {{-- Event Meta Info --}}
                        <div class="border-t border-slate-100 pt-4 space-y-2 text-xs text-slate-500">
                            {{-- Date --}}
                            <div class="flex items-center gap-2">
                                <span class="text-slate-400 w-4 text-center"><i class="fa-regular fa-calendar"></i></span>
                                <span class="font-medium text-slate-600">
                                    {{ $event->tanggal_mulai ? $event->tanggal_mulai->locale('id')->translatedFormat('d M Y') : '-' }}
                                    @if($event->tanggal_selesai && $event->tanggal_selesai != $event->tanggal_mulai)
                                        - {{ $event->tanggal_selesai->locale('id')->translatedFormat('d M Y') }}
                                    @endif
                                </span>
                            </div>
 
                            {{-- Time --}}
                            <div class="flex items-center gap-2">
                                <span class="text-slate-400 w-4 text-center"><i class="fa-regular fa-clock"></i></span>
                                <span class="font-medium text-slate-600">
                                    {{ $event->jam_mulai ? substr($event->jam_mulai, 0, 5) : '--:--' }} 
                                    @if($event->jam_selesai)
                                        - {{ substr($event->jam_selesai, 0, 5) }}
                                    @endif
                                    WIB
                                </span>
                            </div>
 
                            {{-- Location --}}
                            <div class="flex items-center gap-2">
                                <span class="text-slate-400 w-4 text-center"><i class="fa-solid fa-location-dot"></i></span>
                                <span class="font-medium text-slate-600 truncate">
                                    @if(filter_var($event->lokasi, FILTER_VALIDATE_URL))
                                        Tautan Peta (Google Maps)
                                    @else
                                        {{ $event->lokasi ?? '-' }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
 
        {{-- Pagination --}}
        @if($events->hasPages())
            <div class="pt-10 flex justify-center">
                {{ $events->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
