@extends('layouts.petugas')

@section('title', 'Kelola Pengajuan Peminjaman')

@section('content')
<div class="mx-auto max-w-[1280px]">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Pengajuan Peminjaman</h2>
            <p class="text-sm text-slate-500 mt-1.5">Kelola daftar permohonan peminjaman buku dari anggota.</p>
        </div>
        <div>
            <a href="{{ route('petugas.peminjaman.export', request()->query()) }}" class="inline-flex items-center justify-center gap-2.5 px-5 py-3 bg-white border border-slate-200 text-slate-700 hover:text-slate-900 font-semibold text-sm rounded-xl hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-file-arrow-down text-base"></i> Ekspor Laporan
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Menunggu -->
        <div class="bg-white rounded-2xl p-6 shadow-sm flex items-center gap-5 border border-slate-100/60 transition hover:shadow-md">
            <div class="h-14 w-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                <i class="fa-regular fa-clock text-2xl"></i>
            </div>
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">MENUNGGU</p>
                <h3 class="text-3xl font-extrabold mt-1 text-slate-800">{{ number_format($stats['menunggu']) }}</h3>
            </div>
        </div>

        <!-- Disetujui Hari Ini -->
        <div class="bg-white rounded-2xl p-6 shadow-sm flex items-center gap-5 border border-slate-100/60 transition hover:shadow-md">
            <div class="h-14 w-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i class="fa-regular fa-circle-check text-2xl"></i>
            </div>
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">DISETUJUI HARI INI</p>
                <h3 class="text-3xl font-extrabold mt-1 text-slate-800">{{ number_format($stats['disetujui_hari_ini']) }}</h3>
            </div>
        </div>

        <!-- Ditolak Hari Ini -->
        <div class="bg-white rounded-2xl p-6 shadow-sm flex items-center gap-5 border border-slate-100/60 transition hover:shadow-md">
            <div class="h-14 w-14 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600">
                <i class="fa-regular fa-circle-xmark text-2xl"></i>
            </div>
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">DITOLAK HARI INI</p>
                <h3 class="text-3xl font-extrabold mt-1 text-slate-800">{{ number_format($stats['ditolak_hari_ini']) }}</h3>
            </div>
        </div>

        <!-- Total Sirkulasi Aktif -->
        <div class="bg-white rounded-2xl p-6 shadow-sm flex items-center gap-5 border border-slate-100/60 transition hover:shadow-md">
            <div class="h-14 w-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
                <i class="fa-solid fa-book-open text-2xl"></i>
            </div>
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">TOTAL SIRKULASI AKTIF</p>
                <h3 class="text-3xl font-extrabold mt-1 text-slate-800">{{ number_format($stats['total_sirkulasi']) }}</h3>
            </div>
        </div>
    </div>

    <!-- Alerts if any -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Controls Bar -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Filter Status Tab List -->
            <div class="flex flex-wrap gap-2">
                @foreach(['semua' => 'Semua', 'menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'] as $val => $label)
                    <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}" 
                       class="px-4 py-2 text-xs font-bold rounded-lg transition {{ $statusFilter === $val ? 'bg-slate-100 text-slate-850' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <!-- Search Field -->
            <form action="{{ request()->url() }}" method="GET" class="relative w-full sm:w-[320px]">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <span class="absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Filter hasil..." class="w-full text-xs rounded-xl border border-slate-200 bg-slate-50/70 py-2.5 pl-9 pr-4 text-slate-700 outline-none transition focus:border-slate-300 focus:bg-white">
            </form>
        </div>

        <!-- Table Grid -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">ID Pengajuan</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Anggota</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Judul Buku</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Tanggal</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Status</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($peminjamans as $p)
                        <tr class="hover:bg-slate-50/40 transition">
                            <!-- ID Pengajuan -->
                            <td class="px-6 py-5 text-sm font-semibold text-slate-700 whitespace-nowrap">
                                #{{ $p->kode_peminjaman }}
                            </td>
                            
                            <!-- Anggota Info -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    @php
                                        $initials = '';
                                        if ($p->anggota && $p->anggota->nama_lengkap) {
                                            $words = explode(' ', $p->anggota->nama_lengkap);
                                            $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                        } else {
                                            $initials = 'A';
                                        }
                                    @endphp
                                    <div class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center font-bold text-xs text-slate-700">
                                        {{ $initials }}
                                    </div>
                                    <div class="leading-none">
                                        <span class="block text-sm font-semibold text-slate-800">{{ $p->anggota->nama_lengkap ?? 'Anggota' }}</span>
                                        <span class="block text-[11px] text-slate-400 mt-1">{{ $p->anggota->no_anggota ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Judul Buku -->
                            <td class="px-6 py-5">
                                <div class="leading-tight max-w-[280px]">
                                    <span class="block text-sm font-semibold text-slate-800 line-clamp-1">
                                        {{ $p->detailPeminjaman->first()?->buku?->judul ?? 'Buku' }}
                                    </span>
                                    <span class="block text-[11px] text-slate-400 mt-1">
                                        ISBN: {{ $p->detailPeminjaman->first()?->buku?->isbn ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Tanggal -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="leading-none">
                                    <span class="block text-sm text-slate-700">
                                        {{ $p->tanggal_pengajuan ? $p->tanggal_pengajuan->locale('id')->translatedFormat('d M Y') : ($p->created_at ? $p->created_at->locale('id')->translatedFormat('d M Y') : '-') }}
                                    </span>
                                    <span class="block text-[11px] text-slate-400 mt-1">
                                        {{ $p->tanggal_pengajuan ? $p->tanggal_pengajuan->format('H:i') : ($p->created_at ? $p->created_at->format('H:i') : '-') }} WIB
                                    </span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                @php
                                    $status = strtolower($p->status_peminjaman);
                                    $badge = 'bg-slate-50 text-slate-600';
                                    $statusLabel = str_replace('_', ' ', $p->status_peminjaman);
                                    if ($status === 'diajukan') {
                                        $badge = 'bg-amber-50 text-amber-600';
                                        $statusLabel = 'Diajukan';
                                    } elseif ($status === 'siap_diambil') {
                                        $badge = 'bg-blue-50 text-blue-600';
                                        $statusLabel = 'Siap Diambil';
                                    } elseif (in_array($status, ['aktif', 'terlambat'])) {
                                        $badge = 'bg-emerald-50 text-emerald-600';
                                        $statusLabel = $status === 'aktif' ? 'Aktif' : 'Terlambat';
                                    } elseif ($status === 'selesai') {
                                        $badge = 'bg-slate-100 text-slate-650';
                                        $statusLabel = 'Selesai';
                                    } elseif (in_array($status, ['ditolak', 'dibatalkan'])) {
                                        $badge = 'bg-rose-50 text-rose-600';
                                        $statusLabel = $status === 'ditolak' ? 'Ditolak' : 'Dibatalkan';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold capitalize {{ $badge }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-5 text-center whitespace-nowrap">
                                <div class="inline-flex items-center justify-center gap-3">
                                    <!-- Detail -->
                                    <a href="{{ route('petugas.peminjaman.show', $p->id_peminjaman) }}" class="text-slate-400 hover:text-slate-700 transition" title="Lihat Detail">
                                        <i class="fa-regular fa-eye text-base"></i>
                                    </a>

                                    @if(in_array($status, ['menunggu', 'pending', 'pengajuan', 'diajukan']))
                                        <!-- Approve Link -->
                                        <a href="{{ route('petugas.peminjaman.approve-form', $p->id_peminjaman) }}" class="text-emerald-500 hover:text-emerald-700 transition" title="Setujui & Atur Jadwal">
                                            <i class="fa-solid fa-check text-base"></i>
                                        </a>

                                        <!-- Reject Action -->
                                        <form action="{{ route('petugas.peminjaman.tolak', $p->id_peminjaman) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menolak pengajuan ini?')">
                                            @csrf
                                            <button type="submit" class="text-rose-500 hover:text-rose-700 transition" title="Tolak Pengajuan">
                                                <i class="fa-solid fa-xmark text-base"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-400">
                                Belum ada data pengajuan peminjaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($peminjamans->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs text-slate-500">
                    Menampilkan {{ $peminjamans->firstItem() }}-{{ $peminjamans->lastItem() }} dari {{ $peminjamans->total() }} entri
                </p>
                <div>
                    {{ $peminjamans->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
