@extends('layouts.public')

@section('title', $prestasi->judul_prestasi.' - SIPADI Bukittinggi')

@section('content')
@php
    $tingkatLabels = [
        'lokal' => 'Lokal',
        'nasional' => 'Nasional',
        'internasional' => 'Internasional',
    ];
    $lampiranUrl = $prestasi->file_lampiran ? Storage::url($prestasi->file_lampiran) : null;
    $lampiranExtension = $prestasi->file_lampiran ? strtolower(pathinfo($prestasi->file_lampiran, PATHINFO_EXTENSION)) : null;
    $lampiranIsImage = in_array($lampiranExtension, ['jpg', 'jpeg', 'png', 'webp'], true);
@endphp

<div class="mx-auto max-w-7xl px-6 py-10 lg:px-12" x-data="{ attachmentOpen: false }" @keydown.escape.window="attachmentOpen = false">
    <nav class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="{{ route('landing') }}" class="hover:text-[#04241e]">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <a href="{{ route('prestasi.public.index') }}" class="hover:text-[#04241e]">Prestasi</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="max-w-xs truncate text-slate-700">{{ $prestasi->judul_prestasi }}</span>
    </nav>

    <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_320px]">
        <main class="min-w-0 space-y-6">
            <article class="overflow-hidden rounded-3xl border border-slate-100 bg-white p-6 shadow-sm lg:p-8">
                <div class="overflow-hidden rounded-3xl bg-slate-100">
                    @if ($prestasi->gambar)
                        <img src="{{ Storage::url($prestasi->gambar) }}" alt="{{ $prestasi->judul_prestasi }}" class="max-h-[520px] w-full object-cover">
                    @else
                        <div class="flex aspect-[16/7] min-h-72 w-full items-center justify-center bg-gradient-to-br from-[#04241e] via-[#0f4b3e] to-[#d7a928] text-7xl text-[#ffdc7c]">
                            <i class="fa-solid fa-trophy"></i>
                        </div>
                    @endif
                </div>

                <div class="mt-7 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                    <span class="rounded-full bg-[#d7a928] px-3 py-1 text-[10px] font-black uppercase tracking-wider text-white">{{ $tingkatLabels[$prestasi->tingkat_prestasi] ?? 'Prestasi' }}</span>
                    <span class="flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar"></i>
                        {{ $prestasi->tanggal_prestasi?->locale('id')->translatedFormat('d F Y') ?? 'Tanggal belum tersedia' }}
                    </span>
                </div>

                <h1 class="mt-4 break-words font-serif text-3xl font-bold leading-tight text-[#04241e] lg:text-5xl">
                    {{ $prestasi->judul_prestasi }}
                </h1>
                <p class="mt-3 text-sm font-semibold text-emerald-800">{{ $prestasi->penyelenggara ?: 'SIPADI Bukittinggi' }}</p>

                <div class="mt-8 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-[11px] font-black uppercase tracking-wider text-slate-400">Penyelenggara</p>
                        <p class="mt-2 text-sm font-bold text-[#04241e]">{{ $prestasi->penyelenggara ?: '-' }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-[11px] font-black uppercase tracking-wider text-slate-400">Nomor Sertifikat/SK</p>
                        <p class="mt-2 text-sm font-bold text-[#04241e]">{{ $prestasi->nomor_sertifikat ?: '-' }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-[11px] font-black uppercase tracking-wider text-slate-400">Tahun</p>
                        <p class="mt-2 text-sm font-bold text-[#04241e]">{{ $prestasi->tanggal_prestasi?->format('Y') ?? '-' }}</p>
                    </div>
                </div>

                <section class="mt-8 border-t border-slate-100 pt-8">
                    <h2 class="font-serif text-2xl font-bold text-[#04241e]">Cerita Prestasi</h2>
                    <p class="mt-4 whitespace-pre-line text-sm leading-8 text-slate-600 lg:text-base">{{ $prestasi->deskripsi ?: 'Deskripsi prestasi belum tersedia.' }}</p>
                </section>
            </article>
        </main>

        <aside class="space-y-6">
            <section class="rounded-3xl bg-[#04241e] p-6 text-white shadow-sm">
                <div class="flex h-14 w-14 items-center justify-center rounded-full border border-[#ffdc7c]/40 text-[#ffdc7c]">
                    <i class="fa-solid fa-award text-xl"></i>
                </div>
                <h3 class="mt-5 font-serif text-xl font-bold">Ringkasan</h3>
                <div class="mt-5 space-y-4 text-sm">
                    <div class="flex justify-between gap-4 border-b border-white/10 pb-3">
                        <span class="text-slate-300">Tingkat</span>
                        <strong>{{ $tingkatLabels[$prestasi->tingkat_prestasi] ?? '-' }}</strong>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-white/10 pb-3">
                        <span class="text-slate-300">Tanggal</span>
                        <strong>{{ $prestasi->tanggal_prestasi?->format('d M Y') ?? '-' }}</strong>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-slate-300">Status</span>
                        <strong class="text-[#ffdc7c]">Terbit</strong>
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <h3 class="font-serif text-lg font-bold text-[#04241e]">Lampiran</h3>
                @if ($lampiranUrl)
                    <button type="button" @click="attachmentOpen = true" class="mt-4 inline-flex h-11 w-full items-center justify-center gap-2 rounded-2xl border border-[#d7a928] bg-[#fff8df] text-sm font-bold text-[#8a6408] transition hover:bg-[#ffefb1]">
                        <i class="fa-regular fa-file-lines"></i>
                        Pratinjau Lampiran
                    </button>
                    <a href="{{ $lampiranUrl }}" target="_blank" class="mt-3 inline-flex w-full items-center justify-center gap-2 text-xs font-bold text-slate-500 transition hover:text-[#04241e]">
                        <i class="fa-solid fa-up-right-from-square"></i>
                        Buka di Tab Baru
                    </a>
                @else
                    <p class="mt-3 text-sm leading-6 text-slate-500">Lampiran belum tersedia untuk prestasi ini.</p>
                @endif
            </section>

            <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <h3 class="font-serif text-lg font-bold text-[#04241e]">Prestasi Lainnya</h3>
                <div class="mt-5 space-y-4">
                    @forelse ($relatedPrestasi as $related)
                        <a href="{{ route('prestasi.public.show', $related->slug) }}" class="flex gap-3 rounded-2xl p-2 transition hover:bg-slate-50">
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-100 text-[#d7a928]">
                                @if ($related->gambar)
                                    <img src="{{ Storage::url($related->gambar) }}" alt="{{ $related->judul_prestasi }}" class="h-full w-full object-cover">
                                @else
                                    <i class="fa-solid fa-trophy"></i>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h4 class="line-clamp-2 text-sm font-bold leading-snug text-[#04241e]">{{ $related->judul_prestasi }}</h4>
                                <p class="mt-1 text-xs text-slate-500">{{ $related->tanggal_prestasi?->locale('id')->translatedFormat('d M Y') ?? 'Tanggal belum tersedia' }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada prestasi lain.</p>
                    @endforelse
                </div>
                <a href="{{ route('prestasi.public.index') }}" class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-[#9a6f08] hover:text-[#04241e]">
                    Lihat Semua Prestasi
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </section>
        </aside>
    </div>

    @if ($lampiranUrl)
        <div x-show="attachmentOpen" x-cloak x-transition.opacity class="fixed inset-0 z-[80] flex items-center justify-center bg-black/80 p-4" @click.self="attachmentOpen = false">
            <div class="flex max-h-[92vh] w-full max-w-5xl flex-col overflow-hidden rounded-3xl bg-white shadow-2xl" x-transition.scale.95>
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
                    <div class="min-w-0">
                        <h3 class="truncate font-serif text-lg font-bold text-[#04241e]">Pratinjau Lampiran</h3>
                        <p class="mt-0.5 truncate text-xs text-slate-500">{{ $prestasi->judul_prestasi }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ $lampiranUrl }}" target="_blank" class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 px-3 text-xs font-bold text-slate-600 transition hover:bg-slate-50">
                            <i class="fa-solid fa-up-right-from-square"></i>
                            Tab Baru
                        </a>
                        <button type="button" @click="attachmentOpen = false" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#04241e] text-white transition hover:bg-[#0a3f35]" aria-label="Tutup pratinjau lampiran">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <div class="min-h-0 flex-1 bg-slate-100 p-4">
                    @if ($lampiranIsImage)
                        <div class="flex max-h-[74vh] items-center justify-center overflow-auto rounded-2xl bg-slate-950 p-4">
                            <img src="{{ $lampiranUrl }}" alt="Lampiran {{ $prestasi->judul_prestasi }}" class="max-h-[70vh] max-w-full object-contain">
                        </div>
                    @else
                        <iframe src="{{ $lampiranUrl }}" title="Lampiran {{ $prestasi->judul_prestasi }}" class="h-[74vh] w-full rounded-2xl border-0 bg-white"></iframe>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
