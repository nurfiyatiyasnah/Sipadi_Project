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
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-150">
                        <th class="pb-3.5 text-xs font-extrabold uppercase tracking-wider text-slate-400">Kode Transaksi</th>
                        <th class="pb-3.5 text-xs font-extrabold uppercase tracking-wider text-slate-400">Nama Anggota</th>
                        <th class="pb-3.5 text-xs font-extrabold uppercase tracking-wider text-slate-400">Judul Buku</th>
                        <th class="pb-3.5 text-xs font-extrabold uppercase tracking-wider text-slate-400">Tanggal Pinjam</th>
                        <th class="pb-3.5 text-xs font-extrabold uppercase tracking-wider text-slate-400">Tanggal Pengembalian</th>
                        <th class="pb-3.5 text-xs font-extrabold uppercase tracking-wider text-slate-400">Status Pengembalian</th>
                        <th class="pb-3.5 text-xs font-extrabold uppercase tracking-wider text-slate-400">Keterlambatan</th>
                        <th class="pb-3.5 text-xs font-extrabold uppercase tracking-wider text-slate-400">Kondisi Buku</th>
                        <th class="pb-3.5 text-xs font-extrabold uppercase tracking-wider text-slate-400">Sanksi (Hari)</th>
                        <th class="pb-3.5 text-xs font-extrabold uppercase tracking-wider text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
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
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 font-bold text-slate-800 text-sm">
                                {{ $peminjaman?->kode_peminjaman }}
                            </td>
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-650 border border-slate-200">
                                        {{ strtoupper($initials) }}
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-800 leading-tight">{{ $anggota?->nama_lengkap }}</h4>
                                        <span class="text-[10px] text-slate-450">{{ $anggota?->no_anggota }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-850 leading-tight">{{ Str::limit($buku?->judul ?? '-', 30) }}</h4>
                                    <span class="text-[10px] text-slate-450">{{ $buku?->kategori?->nama_kategori ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-4 text-xs font-semibold text-slate-600">
                                {{ $peminjaman?->tanggal_diambil ? $peminjaman->tanggal_diambil->locale('id')->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="py-4 text-xs font-semibold text-slate-600">
                                {{ $pengembalian->tanggal_pengembalian ? $pengembalian->tanggal_pengembalian->locale('id')->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="py-4">
                                @if($pengembalian->status_pengembalian === 'Tepat Waktu')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600">
                                        Tepat Waktu
                                    </span>
                                @elseif($pengembalian->status_pengembalian === 'Terlambat')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600">
                                        Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600">
                                        {{ $pengembalian->status_pengembalian }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 text-xs font-semibold text-slate-600 text-center md:text-left">
                                {{ $pengembalian->total_hari_terlambat > 0 ? $pengembalian->total_hari_terlambat . ' Hari' : '-' }}
                            </td>
                            <td class="py-4 text-xs font-semibold text-slate-700">
                                {{ $pengembalian->detailPengembalian?->first()?->kondisi_buku ?? 'Baik' }}
                            </td>
                            <td class="py-4 text-xs font-semibold text-slate-600">
                                {{ $duration > 0 ? $duration . ' Hari' : '0 Hari' }}
                            </td>
                            <td class="py-4">
                                <a href="{{ route('petugas.pengembalian.show', $peminjaman->id_peminjaman) }}" class="h-7 w-7 rounded-lg bg-slate-50 hover:bg-slate-100 flex items-center justify-center border border-slate-200 text-slate-500 hover:text-slate-700 transition">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-slate-400 font-semibold text-sm">
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
