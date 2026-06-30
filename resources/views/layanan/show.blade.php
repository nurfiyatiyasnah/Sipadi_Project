@extends('layouts.petugas')

@section('title', 'Detail Layanan')

@section('content')
@php
    $requirements = collect(preg_split('/\r\n|\r|\n/', (string) $layanan->persyaratan))->filter()->values();
    $procedures = collect(preg_split('/\r\n|\r|\n/', (string) $layanan->prosedur))->filter()->values();
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
                <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-800">SIPADI</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <a href="{{ route('petugas.layanan.index') }}" class="hover:text-slate-800">Layanan</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="font-semibold text-slate-800">Detail Layanan</span>
            </nav>
            <span class="inline-flex rounded-full bg-[#ffe694] px-3 py-1 text-xs font-black uppercase tracking-wider text-[#4c3b05]">Katalog Publik</span>
            <h2 class="mt-3 text-3xl font-black text-slate-900">{{ $layanan->nama_layanan }}</h2>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">{{ $layanan->deskripsi ?: 'Layanan aksesibilitas koleksi antar perpustakaan daerah dalam satu jaringan SIPADI.' }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('petugas.layanan.edit', $layanan) }}" class="inline-flex h-11 items-center gap-2 rounded-xl bg-slate-100 px-5 text-sm font-bold text-slate-700">
                <i class="fa-solid fa-pen"></i>
                Edit Layanan
            </a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1fr_310px]">
        <div class="space-y-6">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center gap-2">
                    <i class="fa-regular fa-circle-info text-[#9a7b13]"></i>
                    <h3 class="font-bold text-slate-900">Informasi Layanan</h3>
                </div>
                <p class="text-sm leading-7 text-slate-600">{{ $layanan->deskripsi ?: 'Layanan ini memungkinkan anggota aktif perpustakaan SIPADI untuk memesan dan meminjam koleksi yang berada di cabang perpustakaan lain tanpa harus mendatangi lokasi fisik cabang tersebut.' }}</p>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-[11px] font-black uppercase tracking-wider text-slate-400">Durasi Layanan</p>
                        <p class="mt-2 font-bold text-slate-900">{{ $layanan->jam_layanan ?: '14 Hari Kalender' }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-[11px] font-black uppercase tracking-wider text-slate-400">Biaya Layanan</p>
                        <p class="mt-2 font-bold text-slate-900">{{ $layanan->biaya ?: 'Gratis' }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-[11px] font-black uppercase tracking-wider text-slate-400">Syarat Keanggotaan</p>
                        <p class="mt-2 font-bold text-slate-900">{{ $requirements->first() ?: 'Minimal Level Gold' }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-[11px] font-black uppercase tracking-wider text-slate-400">Kontak Layanan</p>
                        <p class="mt-2 font-bold text-slate-900">{{ $layanan->kontak_layanan ?: 'SIPADI' }}</p>
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="font-bold text-slate-900">Prosedur Layanan</h3>
                    <span class="text-xs text-slate-400">Update terakhir: {{ $layanan->updated_at?->format('d M Y') ?? now()->format('d M Y') }}</span>
                </div>
                <div class="space-y-5">
                    @forelse ($procedures as $procedure)
                        <div class="flex gap-4">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#0e1f30] text-xs font-black text-white">{{ $loop->iteration }}</span>
                            <p class="text-sm leading-6 text-slate-600">{{ $procedure }}</p>
                        </div>
                    @empty
                        @foreach (['Pengajuan via Aplikasi SIPADI.', 'Verifikasi Admin.', 'Proses Pengiriman.', 'Pengambilan koleksi oleh anggota.'] as $procedure)
                            <div class="flex gap-4">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#0e1f30] text-xs font-black text-white">{{ $loop->iteration }}</span>
                                <p class="text-sm leading-6 text-slate-600">{{ $procedure }}</p>
                            </div>
                        @endforeach
                    @endforelse
                </div>
            </section>
        </div>

        <aside class="space-y-5">
            <section class="rounded-xl border-l-4 border-[#9a7b13] bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-black uppercase tracking-wider text-slate-500">Status Layanan</h3>
                    <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                </div>
                <p class="mt-4 text-2xl font-black text-slate-900">{{ $layanan->status_layanan === 'aktif' ? 'Aktif & Berjalan' : Str::title($layanan->status_layanan ?? 'Nonaktif') }}</p>
                <p class="mt-2 text-sm text-slate-500">Layanan ini sedang tersedia untuk pengajuan anggota.</p>
            </section>

            <section class="overflow-hidden rounded-xl bg-white shadow-sm">
                @if ($layanan->gambar)
                    <img src="{{ Storage::url($layanan->gambar) }}" alt="{{ $layanan->nama_layanan }}" class="aspect-video w-full object-cover">
                @else
                    <div class="flex aspect-video items-center justify-center bg-gradient-to-br from-[#12324a] via-[#2a6f7d] to-[#ffd15c] text-5xl text-white">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                @endif
                <div class="p-4">
                    <button type="button" class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-xl bg-[#0e1f30] text-sm font-bold text-white">
                        <i class="fa-solid fa-upload"></i>
                        Unggah Banner Layanan
                    </button>
                </div>
            </section>

            <section class="rounded-xl bg-white p-5 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900">Statistik Penggunaan</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600">
                    <div class="flex justify-between"><span>Total Penggunaan</span><strong>1,284 Kali</strong></div>
                    <div class="flex justify-between"><span>Rating Layanan</span><strong>4.9/5.0</strong></div>
                    <div class="flex justify-between"><span>Waktu Rata-rata</span><strong>2.4 Hari</strong></div>
                </div>
                <div class="mt-5 h-2 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full w-4/5 rounded-full bg-[#9a7b13]"></div>
                </div>
            </section>

            <section class="rounded-xl bg-white p-5 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900">Penanggung Jawab</h3>
                <div class="mt-4 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#ffe694] font-black text-[#6f5510]">
                        {{ Str::of($layanan->createdBy?->nama_petugas ?? 'AP')->substr(0, 2)->upper() }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-900">{{ $layanan->createdBy?->nama_petugas ?? 'Admin Perpus' }}</p>
                        <p class="text-xs text-slate-500">Koordinator Layanan</p>
                    </div>
                </div>
            </section>
        </aside>
    </div>
</div>
@endsection
