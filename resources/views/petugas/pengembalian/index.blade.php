@extends('layouts.petugas')

@section('title', 'Daftar Pengembalian Buku')

@section('content')
@php use Carbon\Carbon; @endphp
<div class="mx-auto max-w-[1280px]">
    <!-- Breadcrumb -->
    <nav class="flex text-xs font-semibold text-slate-400 mb-6 gap-2 items-center">
        <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-600 transition">Dashboard</a>
        <span>&gt;</span>
        <span class="hover:text-slate-600 transition">Pengembalian</span>
        <span>&gt;</span>
        <span class="text-slate-600 font-bold">Daftar Pengembalian Buku</span>
    </nav>

    <!-- Header Block -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Daftar Pengembalian Buku</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola daftar buku yang sedang dipinjam dan proses pengembalian buku oleh anggota.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('petugas.pengembalian.riwayat') }}" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-xl hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-clock-rotate-left"></i> Lihat Riwayat
            </a>
            <button type="button" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-xl hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-sliders text-xs"></i> Filter
            </button>
            <a href="{{ route('petugas.pengembalian.export-csv', request()->query()) }}" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-xl hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-download text-xs"></i> Export
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-250 text-emerald-800 text-sm font-semibold flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Total Peminjaman Aktif -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex justify-between items-start relative overflow-hidden">
            <div class="z-10">
                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">TOTAL PEMINJAMAN AKTIF</span>
                <h3 class="text-3xl font-black text-slate-850 mt-2">{{ number_format($stats['total_aktif']) }}</h3>
                <div class="flex items-center gap-1.5 mt-2.5">
                    <span class="text-xs font-bold text-emerald-600">
                        <i class="fa-solid fa-arrow-trend-up"></i> +12%
                    </span>
                    <span class="text-[11px] text-slate-400 font-semibold">dari bulan lalu</span>
                </div>
            </div>
            <div class="text-slate-100 text-5xl font-bold absolute right-4 top-6 select-none z-0">
                <i class="fa-solid fa-right-left"></i>
            </div>
        </div>

        <!-- Card 2: Terlambat -->
        <div class="bg-orange-50/50 rounded-2xl p-6 border border-orange-100 shadow-sm flex justify-between items-start relative overflow-hidden">
            <div class="z-10">
                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-800">TERLAMBAT (OVERDUE)</span>
                <h3 class="text-3xl font-black text-amber-900 mt-2">{{ number_format($stats['total_terlambat']) }}</h3>
                <div class="flex items-center gap-1.5 mt-2.5 text-rose-600 font-bold text-xs">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>Butuh Tindakan Segera</span>
                </div>
            </div>
            <div class="text-orange-100/70 text-5xl font-bold absolute right-4 top-6 select-none z-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

        <!-- Card 3: Buku Beredar -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex justify-between items-start relative overflow-hidden">
            <div class="z-10">
                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">BUKU BEREDAR</span>
                <h3 class="text-3xl font-black text-slate-850 mt-2">{{ number_format($stats['buku_beredar']) }}</h3>
                <div class="flex items-center gap-1.5 mt-2.5">
                    <span class="text-[11px] text-slate-450 font-semibold">15% dari total koleksi</span>
                </div>
            </div>
            <div class="text-slate-100 text-5xl font-bold absolute right-4 top-6 select-none z-0">
                <i class="fa-solid fa-book-open"></i>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <!-- Table Search & Filter -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-5 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-850">Daftar Buku Dipinjam</h3>
            
            <form action="{{ route('petugas.pengembalian.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="relative flex-1 md:w-80">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari anggota, judul, kode..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm placeholder:text-slate-400 focus:outline-none focus:border-slate-350 transition">
                </div>
                
                <select name="status" onchange="this.form.submit()" class="px-3.5 py-2 border border-slate-200 rounded-xl text-sm text-slate-700 bg-white focus:outline-none focus:border-slate-350 transition">
                    <option value="semua" {{ $statusFilter === 'semua' ? 'selected' : '' }}>Semua</option>
                    <option value="sedang dipinjam" {{ $statusFilter === 'sedang dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="terlambat" {{ $statusFilter === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
            </form>
        </div>

        <!-- Table Listing -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="pb-3 text-xs font-extrabold uppercase tracking-wider text-slate-400">Kode Peminjaman</th>
                        <th class="pb-3 text-xs font-extrabold uppercase tracking-wider text-slate-400">Nama Anggota</th>
                        <th class="pb-3 text-xs font-extrabold uppercase tracking-wider text-slate-400">Judul Buku</th>
                        <th class="pb-3 text-xs font-extrabold uppercase tracking-wider text-slate-400">Tanggal Pinjam</th>
                        <th class="pb-3 text-xs font-extrabold uppercase tracking-wider text-slate-400">Batas Pengembalian</th>
                        <th class="pb-3 text-xs font-extrabold uppercase tracking-wider text-slate-400">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($peminjamans as $peminjaman)
                        @php
                            $anggota = $peminjaman->anggota;
                            $firstDetail = $peminjaman->detailPeminjaman->first();
                            $buku = $firstDetail?->buku;
                            
                            $firstEksemplar = $buku?->eksemplar?->first();
                            
                            // Check delay
                            $hariTerlambat = 0;
                            $isOverdue = false;
                            if ($peminjaman->tanggal_jatuh_tempo && today()->greaterThan($peminjaman->tanggal_jatuh_tempo)) {
                                $isOverdue = true;
                            } elseif (strtolower($peminjaman->status_peminjaman) === 'terlambat') {
                                $isOverdue = true;
                            }
                            
                            $initials = collect(explode(' ', $anggota?->nama_lengkap ?? 'A'))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition cursor-pointer" onclick="window.location='{{ route('petugas.pengembalian.show', $peminjaman->id_peminjaman) }}'">
                            <td class="py-4 font-bold text-slate-800 text-sm">
                                {{ $peminjaman->kode_peminjaman }}
                            </td>
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-650 border border-slate-200">
                                        {{ strtoupper($initials) }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800 leading-tight">{{ $anggota?->nama_lengkap }}</h4>
                                        <span class="text-xs text-slate-450">{{ $anggota?->no_anggota }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-850 leading-tight">{{ Str::limit($buku?->judul ?? '-', 35) }}</h4>
                                    <span class="text-xs text-slate-450">{{ $firstEksemplar?->kode_eksemplar ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-4 text-slate-600 text-sm">
                                {{ $peminjaman->tanggal_diambil ? $peminjaman->tanggal_diambil->locale('id')->translatedFormat('d M Y') : ($peminjaman->created_at ? $peminjaman->created_at->locale('id')->translatedFormat('d M Y') : '-') }}
                            </td>
                            <td class="py-4 text-sm {{ $isOverdue ? 'text-rose-600 font-bold' : 'text-slate-600' }}">
                                {{ $peminjaman->tanggal_jatuh_tempo ? $peminjaman->tanggal_jatuh_tempo->locale('id')->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="py-4 text-sm">
                                @if($isOverdue)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-600">
                                        ● Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600">
                                        ● Sedang Dipinjam
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 font-semibold text-sm">
                                <i class="fa-solid fa-folder-open text-2xl mb-2 block text-slate-300"></i>
                                Tidak ada data buku dipinjam saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 pt-5 border-t border-slate-100">
            {{ $peminjamans->links() }}
        </div>
    </div>
</div>
@endsection
