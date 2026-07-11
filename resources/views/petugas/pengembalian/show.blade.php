@extends('layouts.petugas')

@section('title', 'Detail Peminjaman Aktif')

@section('content')
@php use Carbon\Carbon; @endphp
<div class="mx-auto max-w-[1280px]">
    <!-- Breadcrumb -->
    <nav class="flex text-xs font-semibold text-slate-400 mb-6 gap-2 items-center">
        <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-600 transition">Dashboard</a>
        <span>&gt;</span>
        <a href="{{ route('petugas.pengembalian.index') }}" class="hover:text-slate-600 transition">Pengembalian</a>
        <span>&gt;</span>
        <span class="text-slate-600 font-bold">Detail Peminjaman Aktif</span>
    </nav>

    <!-- Header Block -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('petugas.pengembalian.index') }}" class="h-10 w-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-700 hover:bg-slate-50 transition shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Detail Peminjaman Aktif: {{ $peminjaman->kode_peminjaman }}</h2>
            </div>
            <div class="mt-2 flex items-center gap-2">
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Status:</span>
                @if($isTerlambat)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-600">
                        Terlambat
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600">
                        Sedang Dipinjam
                    </span>
                @endif
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('petugas.pengembalian.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-sm rounded-xl transition shadow-sm">
                Kembali
            </a>
            <a href="{{ route('petugas.pengembalian.proses-form', $peminjaman->id_peminjaman) }}" class="px-5 py-2.5 bg-[#0e1f30] text-white hover:bg-[#122b42] font-bold text-sm rounded-xl transition shadow-sm">
                <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Proses Pengembalian
            </a>
        </div>
    </div>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Data Anggota & Data Buku -->
        <div class="lg:col-span-7 space-y-8">
            <!-- Data Anggota Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-700">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-850">Data Anggota</h3>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                    @php
                        $initials = collect(explode(' ', $anggota?->nama_lengkap ?? 'A'))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                        $statusAnggota = filled($anggota?->status_anggota) ? $anggota->status_anggota : 'Aktif';
                    @endphp
                    <div class="h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center text-lg font-black text-slate-650 border border-slate-200 shadow-sm flex-shrink-0">
                        {{ strtoupper($initials) }}
                    </div>

                    <div class="flex-1 w-full space-y-4">
                        <div>
                            <h4 class="text-lg font-bold text-slate-850 leading-snug">{{ $anggota?->nama_lengkap }}</h4>
                            <p class="text-xs font-semibold text-slate-400 mt-0.5">ID: {{ $anggota?->no_anggota }}</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 pt-2">
                            <div>
                                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">TIPE ANGGOTA</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-full text-xs font-bold bg-blue-50 text-blue-650">
                                    Anggota
                                </span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">NO. TELEPON</span>
                                <span class="block text-sm font-semibold text-slate-700 mt-0.5">{{ $anggota?->no_telepon ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">STATUS</span>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600">
                                        {{ $statusAnggota }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Buku Card -->
            @php
                $firstDetail = $peminjaman->detailPeminjaman->first();
                $buku = $firstDetail?->buku;
                $firstEksemplar = $buku?->eksemplar?->first();
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-700">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-850">Data Buku</h3>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                    <x-book-cover :book="$buku" class="h-36 w-24 rounded-lg" icon-class="text-3xl" />

                    <div class="flex-1 w-full space-y-4">
                        <div>
                            <h4 class="text-lg font-bold text-slate-850 leading-snug">{{ $buku?->judul }}</h4>
                            <p class="text-xs font-semibold text-slate-400 mt-0.5">{{ $buku?->penulis }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-x-6 gap-y-4 pt-2">
                            <div>
                                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">ISBN</span>
                                <span class="block text-sm font-semibold text-slate-700 mt-0.5">{{ $buku?->isbn ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">PENERBIT</span>
                                <span class="block text-sm font-semibold text-slate-700 mt-0.5">{{ $buku?->penerbit ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">KODE EKSEMPLAR</span>
                                <span class="font-mono text-xs font-semibold block mt-1 px-2 py-0.5 bg-slate-100 rounded text-slate-650 w-fit">
                                    {{ $firstEksemplar?->kode_eksemplar ?? '-' }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">RAK</span>
                                <span class="block text-sm font-semibold text-slate-700 mt-0.5">{{ $firstEksemplar?->lokasi_rak ?: '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Informasi Peminjaman & Status Keterlambatan -->
        <div class="lg:col-span-5 space-y-8">
            <!-- Informasi Peminjaman Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-700">
                        <i class="fa-regular fa-calendar-check"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-850">Informasi Peminjaman</h3>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-xs font-extrabold text-slate-400 uppercase">TANGGAL PINJAM</span>
                        <span class="text-sm font-bold text-slate-750">
                            {{ $peminjaman->tanggal_diambil ? $peminjaman->tanggal_diambil->locale('id')->translatedFormat('d M Y, H:i') : '-' }} WIB
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-xs font-extrabold text-slate-400 uppercase">TENGGAT WAKTU</span>
                        <span class="text-sm font-bold text-slate-750 {{ $isTerlambat ? 'text-rose-600' : '' }}">
                            {{ $peminjaman->tanggal_jatuh_tempo ? $peminjaman->tanggal_jatuh_tempo->locale('id')->translatedFormat('d M Y, 23:59') : '-' }} WIB
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-xs font-extrabold text-slate-400 uppercase">PETUGAS PEMINJAMAN</span>
                        <span class="text-sm font-bold text-slate-750">
                            {{ $peminjaman->petugas?->nama_petugas ?? '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-xs font-extrabold text-slate-400 uppercase">METODE PEMINJAMAN</span>
                        <span class="text-sm font-bold text-slate-750">Di Tempat</span>
                    </div>
                </div>
            </div>

            <!-- Status Keterlambatan Card -->
            @if($isTerlambat)
                <div class="bg-rose-50/30 rounded-2xl border border-rose-100 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fa-solid fa-triangle-exclamation text-rose-550 text-lg"></i>
                        <h4 class="text-sm font-bold text-rose-800">Status Keterlambatan</h4>
                    </div>
                    
                    <p class="text-xs text-rose-700/80">Terhitung sejak {{ $peminjaman->tanggal_jatuh_tempo->locale('id')->translatedFormat('d M Y') }}.</p>
                    
                    <div class="bg-white rounded-xl border border-rose-100 p-4 mt-4 flex items-center justify-between shadow-sm">
                        <span class="text-xs font-bold text-slate-500">Jumlah Hari Terlambat</span>
                        <span class="text-2xl font-black text-rose-600">{{ $hariTerlambat }} Hari</span>
                    </div>
                    
                    <p class="text-[11px] text-slate-400 mt-4 leading-normal">
                        <i class="fa-solid fa-circle-info text-slate-350 mr-1"></i>
                        Denda keterlambatan mungkin berlaku sesuai peraturan perpustakaan.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
