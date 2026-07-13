@extends('layouts.petugas')

@section('title', 'Detail Riwayat Pengembalian')

@section('content')
@php
    $peminjaman = $pengembalian->peminjaman;
    $anggota = $peminjaman?->anggota;
    $firstDetail = $peminjaman?->detailPeminjaman?->first();
    $detailPengembalian = $pengembalian->detailPengembalian->first();
    $buku = $detailPengembalian?->detailPeminjaman?->buku ?? $firstDetail?->buku;
    $firstEksemplar = $buku?->eksemplar?->first();
    $sanksi = $peminjaman?->sanksiAnggota;
    $sanksiHari = $sanksi ? (int) abs($sanksi->tanggal_mulai->diffInDays($sanksi->tanggal_selesai)) : 0;
    $initials = collect(explode(' ', $anggota?->nama_lengkap ?? 'A'))->map(fn ($name) => substr($name, 0, 1))->take(2)->join('');
    $catatanData = is_string($pengembalian->catatan) ? json_decode($pengembalian->catatan, true) : [];
    $catatanData = is_array($catatanData) ? $catatanData : [];
    $catatanKondisi = $detailPengembalian?->catatan ?: ($catatanData['catatan'] ?? null);
    $photoPath = $catatanData['foto'] ?? null;
@endphp

<div class="mx-auto max-w-[1280px]">
    <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-slate-400">
        <a href="{{ route('petugas.dashboard') }}" class="transition hover:text-slate-600">Dashboard</a>
        <span>&gt;</span>
        <a href="{{ route('petugas.pengembalian.index') }}" class="transition hover:text-slate-600">Pengembalian</a>
        <span>&gt;</span>
        <a href="{{ route('petugas.pengembalian.riwayat') }}" class="transition hover:text-slate-600">Riwayat</a>
        <span>&gt;</span>
        <span class="font-bold text-slate-600">Detail {{ $peminjaman?->kode_peminjaman ?? '-' }}</span>
    </nav>

    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex flex-wrap items-center gap-3">
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-800">Detail Riwayat Pengembalian</h2>
                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-600">
                    {{ $pengembalian->status_pengembalian ?? 'Selesai' }}
                </span>
            </div>
            <p class="mt-1 text-sm text-slate-500">Riwayat transaksi pengembalian yang sudah selesai diproses.</p>
        </div>

        <a href="{{ route('petugas.pengembalian.riwayat') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
            <i class="fa-solid fa-chevron-left mr-2 text-xs"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
        <div class="space-y-8 lg:col-span-5">
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <div class="mb-6 flex items-center gap-3 border-b border-slate-100 pb-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 text-slate-700">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-850">Informasi Anggota</h3>
                </div>

                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full border border-slate-200 bg-slate-100 text-base font-extrabold text-slate-650 shadow-sm">
                        {{ strtoupper($initials) }}
                    </div>
                    <div class="flex-1 space-y-3">
                        <div>
                            <h4 class="text-base font-bold text-slate-850">{{ $anggota?->nama_lengkap ?? '-' }}</h4>
                            <p class="mt-0.5 text-xs font-semibold text-slate-400">ID: {{ $anggota?->no_anggota ?? '-' }}</p>
                            <span class="mt-1.5 inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-650">
                                Anggota
                            </span>
                        </div>
                        <div class="space-y-1.5 pt-2 text-xs text-slate-600">
                            <div class="flex justify-between gap-4">
                                <span class="font-semibold text-slate-400">Email</span>
                                <span class="text-right font-bold text-slate-750">{{ $anggota?->user?->email ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="font-semibold text-slate-400">Telepon</span>
                                <span class="text-right font-bold text-slate-750">{{ $anggota?->no_telepon ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <div class="mb-6 flex items-center gap-3 border-b border-slate-100 pb-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 text-slate-700">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-850">Buku Dikembalikan</h3>
                </div>

                <div class="flex gap-4">
                    <x-book-cover :book="$buku" class="h-28 w-20 rounded" icon-class="text-2xl" />
                    <div class="flex-1 space-y-2">
                        <div>
                            <span class="block text-[9px] font-extrabold uppercase tracking-wider text-slate-400">Eksemplar: {{ $firstEksemplar?->kode_eksemplar ?? '-' }}</span>
                            <h4 class="mt-0.5 text-sm font-bold leading-tight text-slate-850">{{ $buku?->judul ?? '-' }}</h4>
                            <p class="mt-0.5 text-xs text-slate-450">{{ $buku?->penulis ?? '-' }}</p>
                        </div>
                        <div class="space-y-1 pt-1.5 text-[11px] text-slate-600">
                            <div class="flex justify-between gap-4">
                                <span class="font-semibold text-slate-400">ISBN</span>
                                <span class="text-right font-semibold text-slate-750">{{ $buku?->isbn ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="font-semibold text-slate-400">Kategori</span>
                                <span class="text-right font-semibold text-slate-750">{{ $buku?->kategori?->nama_kategori ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8 lg:col-span-7">
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <div class="mb-6 flex items-center gap-3 border-b border-slate-100 pb-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 text-slate-700">
                        <i class="fa-regular fa-file-lines"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-850">Detail Transaksi</h3>
                </div>

                <div class="mb-6 grid grid-cols-1 gap-x-6 gap-y-4 text-sm sm:grid-cols-2">
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Kode Peminjaman</span>
                        <span class="mt-0.5 block font-bold text-slate-750">{{ $peminjaman?->kode_peminjaman ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Petugas Pengembalian</span>
                        <span class="mt-0.5 block font-bold text-slate-750">{{ $pengembalian->petugas?->nama_petugas ?? $peminjaman?->petugas?->nama_petugas ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Tanggal Pinjam</span>
                        <span class="mt-0.5 block font-bold text-slate-750">{{ $peminjaman?->tanggal_diambil ? $peminjaman->tanggal_diambil->locale('id')->translatedFormat('d M Y, H:i') : '-' }} WIB</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Tanggal Pengembalian</span>
                        <span class="mt-0.5 block font-bold text-slate-750">{{ $pengembalian->tanggal_pengembalian ? $pengembalian->tanggal_pengembalian->locale('id')->translatedFormat('d M Y, H:i') : '-' }} WIB</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Keterlambatan</span>
                        <span class="mt-0.5 block font-bold {{ $pengembalian->total_hari_terlambat > 0 ? 'text-rose-600' : 'text-slate-750' }}">
                            {{ $pengembalian->total_hari_terlambat > 0 ? $pengembalian->total_hari_terlambat.' Hari' : 'Tidak Terlambat' }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Keadaan Buku</span>
                        <span class="mt-0.5 block font-bold text-slate-750">{{ $detailPengembalian?->kondisi_buku ?? 'Baik' }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Status Peminjaman</span>
                        <span class="mt-1 inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-650">{{ $peminjaman?->status_peminjaman ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Sanksi</span>
                        <span class="mt-0.5 block font-bold text-slate-750">{{ $sanksiHari > 0 ? $sanksiHari.' Hari' : 'Tidak Ada' }}</span>
                    </div>
                </div>

                @if($catatanKondisi)
                    <div class="mb-6 rounded-xl border border-slate-100 bg-slate-50 p-4 text-xs">
                        <span class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Catatan Kondisi</span>
                        <p class="font-semibold leading-relaxed text-slate-700">{{ $catatanKondisi }}</p>
                    </div>
                @endif

                @if($photoPath)
                    <div>
                        <span class="mb-2 block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Bukti Foto</span>
                        <div class="h-40 w-40 overflow-hidden rounded-xl border border-slate-200 bg-slate-50 shadow-sm">
                            <img src="{{ Storage::url($photoPath) }}" alt="Bukti Foto" class="h-full w-full object-cover">
                        </div>
                    </div>
                @endif
            </div>

            @if($sanksi)
                <div class="rounded-2xl border border-rose-100 bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-50 text-rose-600">
                            <i class="fa-solid fa-gavel"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-850">Detail Sanksi</h3>
                    </div>
                    <div class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
                        <div>
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Jenis Sanksi</span>
                            <span class="mt-0.5 block font-bold text-slate-750">{{ $sanksi->jenis_sanksi }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Status</span>
                            <span class="mt-1 inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-bold text-rose-600">{{ $sanksi->status_sanksi }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Tanggal Mulai</span>
                            <span class="mt-0.5 block font-bold text-slate-750">{{ $sanksi->tanggal_mulai?->locale('id')->translatedFormat('d M Y') ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Tanggal Selesai</span>
                            <span class="mt-0.5 block font-bold text-slate-750">{{ $sanksi->tanggal_selesai?->locale('id')->translatedFormat('d M Y') ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
