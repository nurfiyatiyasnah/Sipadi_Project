@extends('layouts.petugas')

@section('title', 'Daftar Aduan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif text-3xl font-bold text-slate-800">Daftar Aduan</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola dan tindak lanjut laporan serta masukan dari anggota perpustakaan.</p>
        </div>
        <div>
            <a href="{{ route('petugas.aduan.arsip') }}" class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 px-5 py-2.5 text-sm font-bold text-slate-600 shadow-sm transition duration-200 flex items-center gap-2">
                <i class="fa-solid fa-box-archive"></i>
                Lihat Arsip
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Table Card Container -->
    <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">
        
        <!-- Filters Header -->
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <form action="{{ route('petugas.aduan.index') }}" method="GET" class="flex flex-wrap items-center gap-4 w-full justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-slate-500">STATUS:</span>
                    <select name="status" onchange="this.form.submit()" class="rounded-xl border border-slate-200/80 bg-slate-50 py-2 px-4 text-sm font-semibold text-slate-700 focus:border-[#ffd56b] focus:ring-[#ffd56b] outline-none">
                        <option value="" {{ !$status ? 'selected' : '' }}>Semua Status</option>
                        <option value="menunggu" {{ $status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="ditanggapi" {{ $status === 'ditanggapi' ? 'selected' : '' }}>Ditanggapi</option>
                        <option value="diarsipkan" {{ $status === 'diarsipkan' ? 'selected' : '' }}>Diarsipkan</option>
                    </select>
                </div>

                <div class="relative w-full max-w-sm">
                    <span class="absolute inset-y-0 left-4 flex items-center text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari ID atau Nama..." 
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-11 pr-4 text-sm focus:bg-white focus:border-[#ffd56b] focus:ring-[#ffd56b] outline-none">
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-4 px-6">ID Aduan</th>
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6">Nama Pengirim</th>
                        <th class="py-4 px-6">Subjek Aduan</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse ($aduanList as $aduan)
                        <tr class="hover:bg-slate-50/40 transition duration-150">
                            <!-- ID -->
                            <td class="py-4 px-6 font-mono font-bold text-slate-900">
                                {{ $aduan->kode_aduan }}
                            </td>
                            <!-- Tanggal -->
                            <td class="py-4 px-6 text-slate-500 whitespace-nowrap">
                                {{ $aduan->created_at->locale('id')->translatedFormat('d M Y') }}
                            </td>
                            <!-- Pengirim -->
                            <td class="py-4 px-6 font-semibold text-slate-800 whitespace-nowrap">
                                {{ $aduan->anggota->nama_lengkap }}
                            </td>
                            <!-- Subjek -->
                            <td class="py-4 px-6 text-slate-500 max-w-xs truncate">
                                {{ $aduan->subjek }}
                            </td>
                            <!-- Status -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @if ($aduan->arsip)
                                    <span class="inline-block bg-slate-100 text-slate-700 border border-slate-200/50 px-3 py-1 text-xs font-bold rounded-full">
                                        Diarsipkan
                                    </span>
                                @elseif ($aduan->status_aduan === 'terkirim')
                                    <span class="inline-block bg-amber-50 text-amber-700 border border-amber-200/50 px-3 py-1 text-xs font-bold rounded-full">
                                        Menunggu
                                    </span>
                                @else
                                    <span class="inline-block bg-emerald-50 text-emerald-700 border border-emerald-200/50 px-3 py-1 text-xs font-bold rounded-full">
                                        Ditanggapi
                                    </span>
                                @endif
                            </td>
                            <!-- Aksi -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <a href="{{ route('petugas.aduan.show', $aduan) }}" class="text-[#0e1f30] hover:text-[#ffd56b] font-bold text-xs inline-flex items-center gap-1 hover:underline transition">
                                    Detail <i class="fa-solid fa-arrow-right-long text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-inbox text-4xl mb-3 block text-slate-300"></i>
                                <span class="text-sm font-semibold">Belum ada aduan masuk.</span>
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
