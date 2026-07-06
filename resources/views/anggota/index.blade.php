@extends('layouts.petugas')
@section('title', 'Daftar Anggota')

@section('content')
<div class="mx-auto max-w-[1280px]">
    <!-- Breadcrumbs -->
    <div class="mb-4 text-xs font-semibold text-slate-500 flex items-center gap-2">
        <span class="hover:text-slate-700">Dashboard</span>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="hover:text-slate-700">Anggota</span>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="text-slate-800">Daftar Anggota</span>
    </div>

    <!-- Header Section -->
    <div class="mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-850">Daftar Anggota</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola data anggota perpustakaan serta pantau status akun dan sanksi anggota.</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm mb-8 border border-slate-100">
        <form method="GET" action="{{ route('petugas.anggota.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <!-- Pencarian -->
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Pencarian</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-4 flex items-center text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama atau NIK..." class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-slate-300 focus:bg-white">
                </div>
            </div>

            <!-- Status Anggota -->
            <div>
                <label for="status" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Status Anggota</label>
                <div class="relative">
                    <select name="status" id="status" onchange="this.form.submit()" class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50/50 bg-none py-3 px-4 text-sm outline-none transition focus:border-slate-300 focus:bg-white pr-10">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <span class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 text-xs">
                        <i class="fa-solid fa-chevron-down"></i>
                    </span>
                </div>
            </div>

            <!-- Status Sanksi -->
            <div class="flex items-center gap-3">
                <div class="flex-1">
                    <label for="sanksi" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Status Sanksi</label>
                    <div class="relative">
                        <select name="sanksi" id="sanksi" onchange="this.form.submit()" class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50/50 bg-none py-3 px-4 text-sm outline-none transition focus:border-slate-300 focus:bg-white pr-10">
                            <option value="all" {{ request('sanksi') === 'all' ? 'selected' : '' }}>Semua</option>
                            <option value="Bebas" {{ request('sanksi') === 'Bebas' ? 'selected' : '' }}>Bebas</option>
                            <option value="Sanksi" {{ request('sanksi') === 'Sanksi' ? 'selected' : '' }}>Sanksi</option>
                            <option value="Diblokir" {{ request('sanksi') === 'Diblokir' ? 'selected' : '' }}>Diblokir</option>
                        </select>
                        <span class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 text-xs">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </div>
                </div>

                @if(request()->filled('search') || request('status', 'all') !== 'all' || request('sanksi', 'all') !== 'all')
                    <a href="{{ route('petugas.anggota.index') }}" class="p-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-100 text-slate-500 transition shadow-sm h-[46px] w-[46px] flex items-center justify-center" title="Reset Filter">
                        <i class="fa-solid fa-filter-circle-xmark"></i>
                    </a>
                @else
                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-400 h-[46px] w-[46px] flex items-center justify-center">
                        <i class="fa-solid fa-filter"></i>
                    </div>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-455">Profil</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-455">Nama Anggota</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-455">NIK</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-455">Status</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-455">Sanksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($anggota as $item)
                        @php
                            // Active sanksi check
                            $activeSanksi = $item->sanksi->where('status_sanksi', 'aktif')->first();
                            $sanksiText = 'Bebas';
                            $sanksiClass = 'bg-slate-100 text-slate-600';
                            if ($activeSanksi) {
                                if (stripos($activeSanksi->jenis_sanksi, 'Blokir') !== false) {
                                    $sanksiText = 'Diblokir';
                                    $sanksiClass = 'bg-rose-50 text-rose-600 border border-rose-100';
                                } else {
                                    $sanksiText = $activeSanksi->jenis_sanksi;
                                    $sanksiClass = 'bg-amber-50 text-amber-700 border border-amber-100';
                                }
                            }
                        @endphp
                        <tr
                            onclick="window.location='{{ route('petugas.anggota.show', $item->id_anggota) }}'"
                            onkeydown="if (event.key === 'Enter' || event.key === ' ') window.location='{{ route('petugas.anggota.show', $item->id_anggota) }}'"
                            role="link"
                            tabindex="0"
                            class="cursor-pointer hover:bg-slate-50/80 focus:bg-slate-50 focus:outline-none transition"
                        >
                            <td class="py-4 px-6">
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_lengkap }}" class="h-10 w-10 rounded-full object-cover border border-slate-200 shadow-sm">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-500 border border-slate-200">
                                        {{ strtoupper(substr($item->nama_lengkap, 0, 2)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800 text-sm">
                                    {{ $item->nama_lengkap }}
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $item->user?->email }}</div>
                            </td>
                            <td class="py-4 px-6 text-sm text-slate-550">{{ $item->nik }}</td>
                            <td class="py-4 px-6">
                                @if (strtolower($item->status_anggota) === 'aktif')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-550"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold {{ $sanksiClass }}">
                                    @if($sanksiText !== 'Bebas' && $sanksiText !== 'Diblokir')
                                        <i class="fa-solid fa-triangle-exclamation mr-1.5"></i>
                                    @endif
                                    {{ $sanksiText }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 text-sm">
                                <i class="fa-solid fa-users-slash text-3xl mb-3 block"></i>
                                Tidak ada data anggota ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section -->
        @if ($anggota->hasPages() || $anggota->total() > 0)
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-sm text-slate-500">
                <div>
                    Menampilkan {{ $anggota->firstItem() ?? 0 }} sampai {{ $anggota->lastItem() ?? 0 }} dari {{ $anggota->total() }} anggota
                </div>
                <div>
                    {{ $anggota->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
