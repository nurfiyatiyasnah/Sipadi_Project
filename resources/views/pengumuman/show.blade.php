@extends('layouts.petugas')
@section('title', 'Detail Pengumuman')

@section('content')
<div class="mx-auto max-w-[1180px] space-y-6">

    {{-- Breadcrumb & Top Actions --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <nav class="text-xs font-semibold text-slate-400 flex items-center gap-1.5">
            <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-600 transition">SIPADI</a>
            <span>/</span>
            <a href="{{ route('petugas.pengumuman.index') }}" class="hover:text-slate-600 transition">Pengumuman</a>
            <span>/</span>
            <span class="text-slate-600 font-bold">Detail Pengumuman</span>
        </nav>
        <div class="flex items-center gap-3">
            <a href="{{ route('petugas.pengumuman.edit', $pengumuman) }}"
               class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                <i class="fa-regular fa-pen-to-square"></i>
                Edit Pengumuman
            </a>
            <button type="button"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-[#0e1f30] hover:bg-[#1a344f] px-4 text-xs font-bold text-white transition shadow-sm">
                <i class="fa-solid fa-share-nodes"></i>
                Bagikan
            </button>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1fr_350px]">

        {{-- Left Column: Main Detail --}}
        <div class="space-y-6">
            {{-- Main Content Card --}}
            <div class="rounded-3xl bg-white p-6 shadow-sm space-y-6">
                {{-- Banner Image --}}
                @if ($pengumuman->gambar)
                    <div class="relative h-96 w-full overflow-hidden rounded-2xl bg-slate-100 border border-slate-100">
                        <img src="{{ Storage::url($pengumuman->gambar) }}" alt="{{ $pengumuman->judul }}"
                             class="h-full w-full object-cover">
                    </div>
                @else
                    <div class="relative h-60 w-full overflow-hidden rounded-2xl bg-gradient-to-br from-[#0e1f30] to-[#1f4565] flex items-center justify-center text-white/20">
                        <i class="fa-solid fa-bullhorn text-7xl"></i>
                    </div>
                @endif

                {{-- Badges & Date --}}
                <div class="flex flex-wrap items-center gap-3">
                    @if ($pengumuman->prioritas === 'Penting')
                        <span class="inline-flex items-center rounded-full bg-amber-100 border border-amber-200 px-3 py-0.5 text-xs font-bold text-amber-700">
                            PENTING
                        </span>
                    @endif
                    <span class="text-xs font-semibold text-slate-400 flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar"></i>
                        {{ $pengumuman->tanggal_mulai ? $pengumuman->tanggal_mulai->locale('id')->translatedFormat('d F Y') : '-' }}
                    </span>
                </div>

                {{-- Title --}}
                <h1 class="font-serif text-3xl font-bold leading-tight text-[#0e1f30]">{{ $pengumuman->judul }}</h1>

                {{-- Body text --}}
                <div class="prose max-w-none text-slate-600 text-sm leading-relaxed whitespace-pre-line font-medium border-t border-slate-100 pt-6">
                    {!! nl2br(e($pengumuman->isi)) !!}
                </div>
            </div>

            {{-- Dokumen Lampiran Card --}}
            @if ($pengumuman->file_lampiran && count($pengumuman->file_lampiran) > 0)
                <div class="rounded-3xl bg-white p-6 shadow-sm space-y-5">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-[#0e1f30] flex items-center gap-2">
                        <i class="fa-solid fa-paperclip"></i>
                        Dokumen Lampiran
                    </h3>
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($pengumuman->file_lampiran as $item)
                            <div class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-slate-50 p-4 relative group">
                                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-slate-400 border border-slate-100 text-xl flex-shrink-0 shadow-sm">
                                    @if(Str::endsWith($item['name'], ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                        <i class="fa-regular fa-image text-emerald-500"></i>
                                    @else
                                        <i class="fa-regular fa-file-pdf text-red-500"></i>
                                    @endif
                                </span>
                                <div class="min-w-0 flex-1 pr-6">
                                    <p class="text-xs font-bold text-slate-700 truncate" title="{{ $item['name'] }}">{{ $item['name'] }}</p>
                                    <p class="text-[10px] font-semibold text-slate-400 mt-0.5">{{ $item['size'] }}</p>
                                </div>
                                <a href="{{ Storage::url($item['path']) }}" download class="absolute right-4 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-[#0e1f30] hover:text-white transition shadow-sm">
                                    <i class="fa-solid fa-download text-xs"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column: Sidebar info --}}
        <div class="space-y-6">
            {{-- Informasi Pengumuman --}}
            <div class="rounded-3xl bg-white p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 border-b border-slate-100 pb-3">Informasi Pengumuman</h3>
                
                <div class="space-y-3.5 text-xs font-semibold text-slate-500">
                    <div class="flex justify-between items-center">
                        <span>Status</span>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold border {{ $pengumuman->status_badge_class }}">
                            {{ $pengumuman->status_label }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Visibilitas</span>
                        <span class="text-slate-800 font-bold">Publik</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Target</span>
                        <span class="text-slate-800 font-bold">{{ $pengumuman->target_pengguna }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Penulis</span>
                        <span class="text-slate-800 font-bold">{{ $pengumuman->petugas?->nama_petugas ?? 'Admin' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Tayangan</span>
                        <span class="text-slate-800 font-bold flex items-center gap-1"><i class="fa-regular fa-eye"></i> {{ number_format($pengumuman->total_views) }}</span>
                    </div>
                </div>
            </div>

            {{-- Pengumuman Lainnya --}}
            @if ($latestPengumuman->isNotEmpty())
                <div class="rounded-3xl bg-white p-6 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 border-b border-slate-100 pb-3">Pengumuman Lainnya</h3>
                    <div class="space-y-4">
                        @foreach ($latestPengumuman as $rel)
                            <a href="{{ route('petugas.pengumuman.show', $rel) }}" class="block group">
                                <span class="text-[10px] font-bold text-amber-600 block">
                                    {{ $rel->tanggal_mulai ? $rel->tanggal_mulai->locale('id')->translatedFormat('d M Y') : '-' }}
                                </span>
                                <h4 class="text-xs font-bold text-slate-800 group-hover:text-[#0e1f30] mt-1 transition line-clamp-2">
                                    {{ $rel->judul }}
                                </h4>
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ route('petugas.pengumuman.index') }}"
                       class="mt-4 flex h-10 w-full items-center justify-center rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        Lihat Semua
                    </a>
                </div>
            @endif

            {{-- Butuh Bantuan --}}
            <div class="rounded-3xl bg-[#0e1f30] p-6 text-white shadow-sm space-y-4 relative overflow-hidden">
                {{-- Decorative pattern --}}
                <div class="absolute -right-4 -bottom-4 opacity-10 text-9xl text-white">
                    <i class="fa-solid fa-headset"></i>
                </div>

                <h3 class="text-sm font-bold">Butuh Bantuan?</h3>
                <p class="text-xs text-slate-300 leading-relaxed font-medium">
                    Kesulitan dalam mengelola konten pengumuman? Hubungi tim teknis IT Center.
                </p>
                <button type="button"
                        class="flex h-10 w-full items-center justify-center rounded-xl bg-[#ffdc7c] hover:bg-[#ebd576] font-bold text-[#071426] text-xs transition">
                    Hubungi Support
                </button>
            </div>
        </div>

    </div>
</div>
@endsection
