@extends('layouts.petugas')

@section('title', 'Riwayat Pengembalian')

@section('content')
@php use Carbon\Carbon; @endphp
<div class="mx-auto max-w-[1280px]">
    <!-- Breadcrumbs & Back Link -->
    <div class="flex items-center justify-between mb-6">
        <nav class="flex text-xs font-semibold text-slate-400 gap-2 items-center">
            <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-600 transition">Dashboard</a>
            <span>&gt;</span>
            <a href="{{ route('petugas.pengembalian.index') }}" class="hover:text-slate-600 transition">Pengembalian</a>
            <span>&gt;</span>
            <span class="text-slate-600 font-bold">Riwayat Pengembalian</span>
        </nav>
        
        <a href="{{ route('petugas.pengembalian.index') }}" class="text-xs font-bold text-[#0e1f30] hover:underline flex items-center gap-1">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Daftar Pengembalian
        </a>
    </div>

    <!-- Header Block -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Riwayat Pengembalian</h2>
            <p class="text-sm text-slate-500 mt-1">Lihat seluruh riwayat peminjaman, pengembalian, keterlambatan, kondisi buku, dan sanksi anggota.</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" class="px-4 py-2.5 bg-[#f0c243] hover:bg-[#d8ae3c] text-slate-850 font-bold text-sm rounded-xl transition shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-file-pdf"></i> Export PDF
            </button>
            <a href="{{ route('petugas.pengembalian.export-csv', request()->query()) }}" class="px-4 py-2.5 bg-[#0e1f30] text-white hover:bg-[#122b42] font-bold text-sm rounded-xl transition shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-file-excel"></i> Export Excel
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

    <!-- Advanced Filter Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-8">
        <form action="{{ route('petugas.pengembalian.riwayat') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Status Peminjaman -->
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2">Status Peminjaman</label>
                    <select name="status_peminjaman" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs text-slate-700 bg-white focus:outline-none focus:border-slate-350">
                        <option value="">Semua Status</option>
                        <option value="selesai" {{ request('status_peminjaman') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status_peminjaman') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <!-- Status Pengembalian -->
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2">Status Pengembalian</label>
                    <select name="status_pengembalian" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs text-slate-700 bg-white focus:outline-none focus:border-slate-350">
                        <option value="">Semua Status</option>
                        <option value="Tepat Waktu" {{ request('status_pengembalian') === 'Tepat Waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                        <option value="Terlambat" {{ request('status_pengembalian') === 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>

                <!-- Status Sanksi -->
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2">Status Sanksi</label>
                    <select name="status_sanksi" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs text-slate-700 bg-white focus:outline-none focus:border-slate-350">
                        <option value="">Semua Sanksi</option>
                        <option value="Ada Sanksi" {{ request('status_sanksi') === 'Ada Sanksi' ? 'selected' : '' }}>Ada Sanksi</option>
                        <option value="Tanpa Sanksi" {{ request('status_sanksi') === 'Tanpa Sanksi' ? 'selected' : '' }}>Tanpa Sanksi</option>
                    </select>
                </div>

                <!-- Kondisi Buku -->
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2">Kondisi Buku</label>
                    <select name="kondisi_buku" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs text-slate-700 bg-white focus:outline-none focus:border-slate-350">
                        <option value="">Semua Kondisi</option>
                        <option value="Baik" {{ request('kondisi_buku') === 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ request('kondisi_buku') === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ request('kondisi_buku') === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        <option value="Hilang" {{ request('kondisi_buku') === 'Hilang' ? 'selected' : '' }}>Hilang</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                <!-- Date Range -->
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="w-full sm:w-auto">
                        <label class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Rentang Tanggal</label>
                        <div class="flex items-center gap-2">
                            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="px-3 py-2 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-slate-350">
                            <span class="text-xs text-slate-400 font-bold">-</span>
                            <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="px-3 py-2 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-slate-350">
                        </div>
                    </div>
                </div>

                <!-- Form Controls -->
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <a href="{{ route('petugas.pengembalian.riwayat') }}" class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700">
                        Reset Filter
                    </a>
                    <button type="submit" class="px-6 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl transition shadow-sm">
                        <i class="fa-solid fa-filter mr-1.5 text-[10px]"></i> Terapkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="min-w-[1320px] w-full table-fixed border-collapse text-left">
                <colgroup>
                    <col class="w-[150px]">
                    <col class="w-[190px]">
                    <col class="w-[230px]">
                    <col class="w-[130px]">
                    <col class="w-[165px]">
                    <col class="w-[155px]">
                    <col class="w-[130px]">
                    <col class="w-[120px]">
                    <col class="w-[115px]">
                    <col class="w-[75px]">
                </colgroup>
                <thead>
                    <tr class="border-b border-slate-150 bg-slate-50/70">
                        <th class="px-3 py-3.5 pl-4 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Kode Transaksi</th>
                        <th class="px-3 py-3.5 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Nama Anggota</th>
                        <th class="px-3 py-3.5 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Judul Buku</th>
                        <th class="px-3 py-3.5 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Tanggal Pinjam</th>
                        <th class="px-3 py-3.5 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Tanggal Pengembalian</th>
                        <th class="px-3 py-3.5 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Status Pengembalian</th>
                        <th class="px-3 py-3.5 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Keterlambatan</th>
                        <th class="px-3 py-3.5 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Kondisi Buku</th>
                        <th class="px-3 py-3.5 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Sanksi</th>
                        <th class="px-3 py-3.5 pr-4 text-center text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pengembalians as $pengembalian)
                        @php
                            $peminjaman = $pengembalian->peminjaman;
                            $anggota = $peminjaman?->anggota;
                            $firstDetail = $peminjaman?->detailPeminjaman?->first();
                            $buku = $firstDetail?->buku;
                            $firstEksemplar = $buku?->eksemplar?->first();
                            $sanksi = $peminjaman?->sanksiAnggota;
                            $duration = $sanksi ? $sanksi->tanggal_mulai->diffInDays($sanksi->tanggal_selesai) : 0;
                            
                            $initials = collect(explode(' ', $anggota?->nama_lengkap ?? 'A'))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                        @endphp
                        <tr class="transition hover:bg-slate-50/60">
                            <td class="px-3 py-4 pl-4 align-middle font-mono text-sm font-bold text-slate-800 whitespace-nowrap">
                                {{ $peminjaman?->kode_peminjaman }}
                            </td>
                            <td class="px-3 py-4 align-middle">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-slate-100 text-xs font-bold text-slate-650">
                                        {{ strtoupper($initials) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="truncate text-xs font-bold leading-tight text-slate-800" title="{{ $anggota?->nama_lengkap }}">{{ $anggota?->nama_lengkap }}</h4>
                                        <span class="block truncate text-[10px] text-slate-450" title="{{ $anggota?->no_anggota }}">{{ $anggota?->no_anggota }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-4 align-middle">
                                <div class="min-w-0">
                                    <h4 class="truncate text-xs font-bold leading-tight text-slate-850" title="{{ $buku?->judul ?? '-' }}">{{ $buku?->judul ?? '-' }}</h4>
                                    <span class="block truncate text-[10px] text-slate-450" title="{{ $buku?->kategori?->nama_kategori ?? '-' }}">{{ $buku?->kategori?->nama_kategori ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-4 align-middle text-xs font-semibold text-slate-600 whitespace-nowrap">
                                {{ $peminjaman?->tanggal_diambil ? $peminjaman->tanggal_diambil->locale('id')->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-3 py-4 align-middle text-xs font-semibold text-slate-600 whitespace-nowrap">
                                {{ $pengembalian->tanggal_pengembalian ? $pengembalian->tanggal_pengembalian->locale('id')->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-3 py-4 align-middle">
                                @if($pengembalian->status_pengembalian === 'Tepat Waktu')
                                    <span class="inline-flex items-center whitespace-nowrap rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-600">
                                        Tepat Waktu
                                    </span>
                                @elseif($pengembalian->status_pengembalian === 'Terlambat')
                                    <span class="inline-flex items-center whitespace-nowrap rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-bold text-amber-600">
                                        Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center whitespace-nowrap rounded-full bg-rose-50 px-2.5 py-1 text-[10px] font-bold text-rose-600">
                                        {{ $pengembalian->status_pengembalian }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-4 align-middle text-xs font-semibold text-slate-600 whitespace-nowrap">
                                {{ $pengembalian->total_hari_terlambat > 0 ? $pengembalian->total_hari_terlambat . ' Hari' : '-' }}
                            </td>
                            <td class="px-3 py-4 align-middle text-xs font-semibold text-slate-700 whitespace-nowrap">
                                {{ $pengembalian->detailPengembalian?->first()?->kondisi_buku ?? 'Baik' }}
                            </td>
                            <td class="px-3 py-4 align-middle text-xs font-semibold text-slate-600 whitespace-nowrap">
                                {{ $duration > 0 ? $duration . ' Hari' : '0 Hari' }}
                            </td>
                            <td class="px-3 py-4 pr-4 align-middle">
                                <a href="{{ route('petugas.pengembalian.riwayat.show', $pengembalian->id_pengembalian) }}" class="mx-auto flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700" title="Lihat detail riwayat pengembalian">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-10 text-center text-sm font-semibold text-slate-400">
                                <i class="fa-solid fa-clock text-2xl mb-2 block text-slate-300"></i>
                                Belum ada riwayat pengembalian buku.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 pt-5 border-t border-slate-100">
            {{ $pengembalians->links() }}
        </div>
    </div>
</div>
@endsection
