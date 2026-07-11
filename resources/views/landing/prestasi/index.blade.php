@extends('layouts.public')

@section('title', 'Prestasi & Penghargaan - SIPADI Bukittinggi')

@section('content')
@php
    $tingkatLabels = [
        'lokal' => 'Lokal',
        'nasional' => 'Nasional',
        'internasional' => 'Internasional',
    ];
@endphp

<div class="bg-[#f6f5e9]">
    <section class="bg-[#04241e] text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 py-14 lg:grid-cols-[1fr_420px] lg:px-12">
            <div class="flex flex-col justify-center">
                <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-slate-300">
                    <a href="{{ route('landing') }}" class="hover:text-[#ffdc7c]">Beranda</a>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <span class="text-[#ffdc7c]">Prestasi</span>
                </nav>
                <span class="mb-4 inline-flex w-fit items-center gap-2 rounded-full border border-[#ffdc7c]/30 bg-[#ffdc7c]/10 px-4 py-2 text-xs font-bold uppercase tracking-wider text-[#ffdc7c]">
                    <i class="fa-solid fa-trophy"></i>
                    Portofolio Prestasi
                </span>
                <h1 class="font-serif text-4xl font-bold leading-tight lg:text-5xl">Prestasi & Penghargaan</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-slate-300">
                    Kumpulan pencapaian, penghargaan, dan apresiasi dari perpustakaan serta kegiatan literasi yang diselenggarakan Dinas Perpustakaan dan Kearsipan Kota Bukittinggi.
                </p>
            </div>

            <div class="hidden rounded-3xl border border-white/10 bg-white/5 p-5 shadow-2xl shadow-black/20 lg:block">
                @if ($featured)
                    <div class="overflow-hidden rounded-2xl bg-slate-900/30">
                        @if ($featured->gambar)
                            <img src="{{ Storage::url($featured->gambar) }}" alt="{{ $featured->judul_prestasi }}" class="aspect-video w-full object-cover">
                        @else
                            <div class="flex aspect-video w-full items-center justify-center bg-gradient-to-br from-[#0c3a30] via-[#164f41] to-[#d7a928] text-5xl text-[#ffdc7c]">
                                <i class="fa-solid fa-award"></i>
                            </div>
                        @endif
                    </div>
                    <div class="pt-5">
                        <span class="inline-flex rounded-full bg-[#ffdc7c] px-3 py-1 text-[10px] font-black uppercase tracking-wider text-[#04241e]">
                            Sorotan Terbaru
                        </span>
                        <h2 class="mt-4 line-clamp-2 font-serif text-2xl font-bold leading-snug text-white">{{ $featured->judul_prestasi }}</h2>
                        <p class="mt-2 line-clamp-1 text-sm font-semibold text-[#ffdc7c]">{{ $featured->penyelenggara ?: 'SIPADI Bukittinggi' }}</p>
                        <div class="mt-4 flex flex-wrap gap-3 text-xs text-slate-300">
                            <span class="inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-medal text-[#ffdc7c]"></i>
                                {{ $tingkatLabels[$featured->tingkat_prestasi] ?? 'Prestasi' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-[#ffdc7c]"></i>
                                {{ $featured->tanggal_prestasi?->locale('id')->translatedFormat('d M Y') ?? 'Tanggal belum tersedia' }}
                            </span>
                        </div>
                        <a href="{{ route('prestasi.public.show', $featured->slug) }}" class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-[#ffdc7c] hover:text-white">
                            Lihat Detail
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                @else
                    <div class="flex h-full min-h-72 flex-col justify-center rounded-2xl border border-white/10 bg-[#092f28] p-6">
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#ffdc7c] text-2xl text-[#04241e]">
                            <i class="fa-solid fa-award"></i>
                        </span>
                        <h2 class="mt-5 font-serif text-2xl font-bold text-white">Ragam Prestasi</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-300">
                            Menampilkan pencapaian institusi, program literasi, lomba, event, dan apresiasi masyarakat yang dikelola melalui SIPADI.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-7xl space-y-8 px-6 py-10 lg:px-12">
        <form method="GET" action="{{ route('prestasi.public.index') }}" class="grid gap-3 rounded-3xl border border-slate-100 bg-white p-5 shadow-sm lg:grid-cols-[1fr_190px_170px_auto]">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input name="search" value="{{ request('search') }}" placeholder="Cari prestasi..." class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm outline-none transition focus:border-[#ffdc7c] focus:ring-[#ffdc7c]">
            </div>
            <select name="tingkat" class="h-12 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-600 focus:border-[#ffdc7c] focus:ring-[#ffdc7c]">
                <option value="">Semua Tingkat</option>
                @foreach ($tingkatLabels as $value => $label)
                    <option value="{{ $value }}" @selected(request('tingkat') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="tahun" class="h-12 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-600 focus:border-[#ffdc7c] focus:ring-[#ffdc7c]">
                <option value="">Semua Tahun</option>
                @foreach ($tahunList as $tahun)
                    <option value="{{ $tahun }}" @selected((string) request('tahun') === (string) $tahun)>{{ $tahun }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-[#04241e] px-5 text-sm font-bold text-white transition hover:bg-[#0a3f35]">
                    <i class="fa-solid fa-filter"></i>
                    Filter
                </button>
                @if (request()->hasAny(['search', 'tingkat', 'tahun']))
                    <a href="{{ route('prestasi.public.index') }}" class="inline-flex h-12 items-center justify-center rounded-2xl border border-slate-200 px-4 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="grid gap-8 lg:grid-cols-[1fr_300px]">
            <main class="min-w-0 space-y-8">
                @if ($items->isNotEmpty())
                    <div class="grid gap-6 md:grid-cols-2">
                        @foreach ($items as $item)
                            <article class="overflow-hidden rounded-3xl border border-slate-100 bg-white p-5 shadow-sm transition hover:shadow-md">
                                <a href="{{ route('prestasi.public.show', $item->slug) }}" class="relative block overflow-hidden rounded-2xl bg-slate-100">
                                    @if ($item->gambar)
                                        <img src="{{ Storage::url($item->gambar) }}" alt="{{ $item->judul_prestasi }}" class="aspect-video w-full object-cover transition duration-300 hover:scale-105">
                                    @else
                                        <div class="flex aspect-video w-full items-center justify-center bg-gradient-to-br from-[#04241e] via-[#0f4b3e] to-[#d7a928] text-4xl text-[#ffdc7c]">
                                            <i class="fa-solid fa-award"></i>
                                        </div>
                                    @endif
                                    <span class="absolute left-4 top-4 rounded-full bg-[#04241e] px-3 py-1 text-[10px] font-black uppercase tracking-wider text-[#ffdc7c]">
                                        {{ $tingkatLabels[$item->tingkat_prestasi] ?? 'Prestasi' }}
                                    </span>
                                </a>
                                <div class="pt-5">
                                    <div class="flex items-center gap-2 text-xs text-slate-500">
                                        <i class="fa-regular fa-calendar"></i>
                                        {{ $item->tanggal_prestasi?->locale('id')->translatedFormat('d M Y') ?? 'Tanggal belum tersedia' }}
                                    </div>
                                    <h3 class="mt-3 font-serif text-xl font-bold leading-snug text-[#04241e]">
                                        <a href="{{ route('prestasi.public.show', $item->slug) }}" class="hover:text-[#a87908]">{{ $item->judul_prestasi }}</a>
                                    </h3>
                                    <p class="mt-2 line-clamp-1 text-sm font-semibold text-emerald-800">{{ $item->penyelenggara ?: 'SIPADI Bukittinggi' }}</p>
                                    <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-600">{{ $item->deskripsi ?: 'Informasi prestasi perpustakaan SIPADI Bukittinggi.' }}</p>
                                    <a href="{{ route('prestasi.public.show', $item->slug) }}" class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-[#9a6f08] hover:text-[#04241e]">
                                        Lihat Detail
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @elseif (! $featured)
                    <div class="rounded-3xl border border-slate-100 bg-white p-12 text-center shadow-sm">
                        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-3xl text-slate-400">
                            <i class="fa-solid fa-trophy"></i>
                        </span>
                        <h3 class="mt-5 font-serif text-xl font-bold text-[#04241e]">Belum Ada Prestasi</h3>
                        <p class="mt-2 text-sm text-slate-500">Belum ada prestasi yang sesuai dengan filter pencarian.</p>
                    </div>
                @endif

                @if ($prestasiPaginated->hasPages())
                    <div class="pt-2">
                        {{ $prestasiPaginated->links() }}
                    </div>
                @endif
            </main>

            <aside class="space-y-6">
                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <h3 class="font-serif text-lg font-bold text-[#04241e]">Tingkat Prestasi</h3>
                    <div class="mt-5 space-y-3 text-sm">
                        <a href="{{ route('prestasi.public.index', request()->except(['tingkat', 'page'])) }}" class="flex items-center justify-between {{ request('tingkat') ? 'text-slate-600 hover:text-[#04241e]' : 'font-bold text-[#04241e]' }}">
                            <span>Semua Prestasi</span>
                            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold">{{ $tingkatCounts['semua'] }}</span>
                        </a>
                        @foreach ($tingkatLabels as $value => $label)
                            <a href="{{ route('prestasi.public.index', array_merge(request()->except('page'), ['tingkat' => $value])) }}" class="flex items-center justify-between {{ request('tingkat') === $value ? 'font-bold text-[#04241e]' : 'text-slate-600 hover:text-[#04241e]' }}">
                                <span>{{ $label }}</span>
                                <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold">{{ $tingkatCounts[$value] }}</span>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-3xl border border-[#ffdc7c]/40 bg-[#fff8df] p-6 shadow-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#04241e] text-[#ffdc7c]">
                        <i class="fa-solid fa-medal"></i>
                    </div>
                    <h3 class="mt-4 font-serif text-lg font-bold text-[#04241e]">Jejak Kinerja</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Prestasi ditampilkan setelah diverifikasi dan dipublikasikan oleh petugas SIPADI.
                    </p>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection
