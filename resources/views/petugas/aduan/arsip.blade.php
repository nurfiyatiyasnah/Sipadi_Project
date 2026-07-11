@extends('layouts.petugas')

@section('title', 'Arsip Aduan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif text-3xl font-bold text-slate-800">Arsip Aduan</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar seluruh aduan yang telah selesai diproses dan diarsipkan.</p>
        </div>
        <div>
            <a href="{{ route('petugas.aduan.index') }}" class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 px-5 py-2.5 text-sm font-bold text-slate-600 shadow-sm transition duration-200 flex items-center gap-2">
                <i class="fa-solid fa-arrow-left-long"></i>
                Kembali ke Daftar Aktif
            </a>
        </div>
    </div>

    <!-- Table Card Container -->
    <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">
        
        <!-- Filters Header -->
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <form action="{{ route('petugas.aduan.arsip') }}" method="GET" class="flex flex-wrap items-center gap-4 w-full justify-between">
                <div class="flex items-center gap-2">
                    <button type="button" class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 px-4 py-2 text-xs font-bold text-slate-600 shadow-sm transition duration-200 flex items-center gap-1.5">
                        <i class="fa-solid fa-sliders"></i> Filter
                    </button>
                    <button type="button" class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 px-4 py-2 text-xs font-bold text-slate-600 shadow-sm transition duration-200 flex items-center gap-1.5">
                        <i class="fa-solid fa-download"></i> Export
                    </button>
                </div>

                <div class="relative w-full max-w-sm">
                    <span class="absolute inset-y-0 left-4 flex items-center text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari arsip aduan..." 
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-11 pr-4 text-sm focus:bg-white focus:border-[#ffd56b] focus:ring-[#ffd56b] outline-none">
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-4 px-6">No. Tiket</th>
                        <th class="py-4 px-6">Tanggal Laporan</th>
                        <th class="py-4 px-6">Pelapor</th>
                        <th class="py-4 px-6">Kategori</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse ($aduanList as $aduan)
                        <tr class="hover:bg-slate-50/40 transition duration-150">
                            <!-- No Tiket -->
                            <td class="py-4 px-6 font-mono font-bold text-slate-900">
                                #{{ $aduan->kode_aduan }}
                            </td>
                            <!-- Tanggal Laporan -->
                            <td class="py-4 px-6 text-slate-500 whitespace-nowrap">
                                {{ $aduan->created_at->locale('id')->translatedFormat('d M Y') }}
                            </td>
                            <!-- Pelapor -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="font-semibold text-slate-800">{{ $aduan->anggota->nama_lengkap }}</div>
                                <div class="text-slate-400 text-xs mt-0.5">{{ $aduan->anggota->kalangan ?? 'Umum' }}</div>
                            </td>
                            <!-- Kategori -->
                            <td class="py-4 px-6 text-slate-500 whitespace-nowrap">
                                {{ $aduan->kategori_aduan }}
                            </td>
                            <!-- Status -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <span class="inline-block bg-slate-100 text-slate-700 border border-slate-200/50 px-3.5 py-0.5 text-xs font-bold rounded-full">
                                    Diarsipkan
                                </span>
                            </td>
                            <!-- Aksi -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <a href="{{ route('petugas.aduan.show', $aduan) }}" class="text-slate-500 hover:text-slate-900 p-1.5 rounded-lg hover:bg-slate-100 transition inline-block" title="Lihat Detail">
                                    <i class="fa-regular fa-eye text-base"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-box-open text-4xl mb-3 block text-slate-300"></i>
                                <span class="text-sm font-semibold">Tidak ada arsip aduan.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($aduanList->hasPages())
            <div class="p-6 border-t border-slate-100">
                {{ $aduanList->appends(request()->query())->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
