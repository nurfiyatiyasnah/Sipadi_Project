@extends('layouts.petugas')

@section('title', 'Detail Prestasi')

@section('content')
@php
    $statusLabels = [
        \App\Models\Prestasi::STATUS_PUBLISHED => 'Terbit',
        \App\Models\Prestasi::STATUS_DRAFT => 'Draft',
        \App\Models\Prestasi::STATUS_INACTIVE => 'Nonaktif',
    ];
    $statusClass = match ($prestasi->status_prestasi) {
        \App\Models\Prestasi::STATUS_PUBLISHED => 'bg-emerald-50 text-emerald-700',
        \App\Models\Prestasi::STATUS_DRAFT => 'bg-amber-50 text-amber-700',
        default => 'bg-slate-100 text-slate-600',
    };
@endphp

<div class="mx-auto max-w-[1180px] space-y-6">
    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
        <div>
            <nav class="mb-4 flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-800">Dashboard</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <a href="{{ route('petugas.prestasi.index') }}" class="hover:text-slate-800">Prestasi</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="font-semibold text-slate-800">Detail Prestasi</span>
            </nav>
            <span class="inline-flex rounded-full bg-[#ffe694] px-3 py-1 text-xs font-black uppercase tracking-wider text-[#4c3b05]">{{ $prestasi->tingkat_prestasi }}</span>
            <h2 class="mt-3 text-3xl font-black text-slate-900">{{ $prestasi->judul_prestasi }}</h2>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">{{ $prestasi->deskripsi ?: 'Deskripsi prestasi belum diisi.' }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('petugas.prestasi.edit', $prestasi) }}" class="inline-flex h-11 items-center gap-2 rounded-xl bg-slate-100 px-5 text-sm font-bold text-slate-700 hover:bg-slate-200">
                <i class="fa-solid fa-pen"></i>
                Edit
            </a>
            <form method="POST" action="{{ route('petugas.prestasi.destroy', $prestasi) }}" onsubmit="return confirm('Yakin ingin menghapus prestasi ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex h-11 items-center gap-2 rounded-xl bg-red-50 px-5 text-sm font-bold text-red-600 hover:bg-red-100">
                    <i class="fa-regular fa-trash-can"></i>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
        <div class="space-y-6">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center gap-2">
                    <i class="fa-regular fa-circle-info text-[#9a7b13]"></i>
                    <h3 class="font-bold text-slate-900">Informasi Utama</h3>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-[11px] font-black uppercase tracking-wider text-slate-400">Penyelenggara</p>
                        <p class="mt-2 font-bold text-slate-900">{{ $prestasi->penyelenggara ?: '-' }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-[11px] font-black uppercase tracking-wider text-slate-400">Tanggal Prestasi</p>
                        <p class="mt-2 font-bold text-slate-900">{{ $prestasi->tanggal_prestasi?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-[11px] font-black uppercase tracking-wider text-slate-400">Nomor Sertifikat/SK</p>
                        <p class="mt-2 font-bold text-slate-900">{{ $prestasi->nomor_sertifikat ?: '-' }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-[11px] font-black uppercase tracking-wider text-slate-400">Dicatat Oleh</p>
                        <p class="mt-2 font-bold text-slate-900">{{ $prestasi->createdBy?->nama_petugas ?? 'Admin Perpus' }}</p>
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="font-bold text-slate-900">Deskripsi Lengkap</h3>
                <p class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-600">{{ $prestasi->deskripsi ?: 'Belum ada deskripsi lengkap.' }}</p>
            </section>
        </div>

        <aside class="space-y-5">
            <section class="rounded-xl border-l-4 border-[#9a7b13] bg-white p-5 shadow-sm">
                <p class="text-sm font-black uppercase tracking-wider text-slate-500">Status Publikasi</p>
                <span class="mt-4 inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">{{ $statusLabels[$prestasi->status_prestasi] ?? 'Nonaktif' }}</span>
            </section>

            <section class="overflow-hidden rounded-xl bg-white shadow-sm">
                @if ($prestasi->gambar)
                    <img src="{{ Storage::url($prestasi->gambar) }}" alt="{{ $prestasi->judul_prestasi }}" class="aspect-video w-full object-cover">
                @else
                    <div class="flex aspect-video items-center justify-center bg-gradient-to-br from-[#12324a] via-[#2a6f7d] to-[#ffd15c] text-5xl text-white">
                        <i class="fa-solid fa-award"></i>
                    </div>
                @endif
            </section>

            <section class="rounded-xl bg-white p-5 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900">Lampiran</h3>
                @if ($prestasi->file_lampiran)
                    <a href="{{ Storage::url($prestasi->file_lampiran) }}" target="_blank" class="mt-4 inline-flex h-10 w-full items-center justify-center gap-2 rounded-xl bg-[#0e1f30] text-sm font-bold text-white">
                        <i class="fa-regular fa-file-lines"></i>
                        Buka Lampiran
                    </a>
                @else
                    <p class="mt-3 text-sm text-slate-500">Belum ada lampiran.</p>
                @endif
            </section>
        </aside>
    </div>
</div>
@endsection
