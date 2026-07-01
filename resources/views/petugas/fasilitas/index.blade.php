@extends('layouts.petugas')

@section('title', 'Daftar Fasilitas')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-[#071426]">Daftar Fasilitas</h2>
        <p class="text-sm text-slate-500 mt-1">Manajemen infrastruktur dan sarana prasarana perpustakaan.</p>
    </div>
    <a href="{{ route('petugas.fasilitas.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#ffd56b] px-4 py-2.5 text-sm font-semibold text-[#071426] transition hover:bg-[#ffc93b] shadow-sm">
        <i class="fa-solid fa-plus"></i>
        <span>Tambah Fasilitas Baru</span>
    </a>
</div>

<!-- Stats -->
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Total Ruangan -->
    <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
            <i class="fa-regular fa-building text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Ruangan</p>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['total_ruangan'] }} Unit</p>
        </div>
    </div>
    
    <!-- Perangkat IT -->
    <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
            <i class="fa-solid fa-laptop text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Perangkat IT</p>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['perangkat_it'] }} Unit</p>
        </div>
    </div>

    <!-- Perlu Perbaikan -->
    <div class="flex items-center gap-4 rounded-xl border border-red-200 bg-white p-5 shadow-sm">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600">
            <i class="fa-solid fa-wrench text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Perlu Perbaikan</p>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['perlu_perbaikan'] }} Unit</p>
        </div>
    </div>

    <!-- Status Baik -->
    <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
            <i class="fa-solid fa-certificate text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Status Baik</p>
            <p class="text-2xl font-bold text-slate-800">{{ $stats['status_baik'] }}%</p>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="mb-8 rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-5 border-b border-slate-200 gap-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('petugas.fasilitas.index') }}" class="rounded-lg {{ $kategori === 'Semua' ? 'bg-slate-100 font-semibold text-slate-800' : 'text-slate-600 hover:bg-slate-50' }} px-4 py-2 text-sm transition">Semua</a>
            <a href="{{ route('petugas.fasilitas.index', ['kategori' => 'Ruangan']) }}" class="rounded-lg {{ $kategori === 'Ruangan' ? 'bg-slate-100 font-semibold text-slate-800' : 'text-slate-600 hover:bg-slate-50' }} px-4 py-2 text-sm transition">Ruangan</a>
            <a href="{{ route('petugas.fasilitas.index', ['kategori' => 'Elektronik']) }}" class="rounded-lg {{ $kategori === 'Elektronik' ? 'bg-slate-100 font-semibold text-slate-800' : 'text-slate-600 hover:bg-slate-50' }} px-4 py-2 text-sm transition">Elektronik</a>
        </div>
        <div class="flex items-center gap-3">
            <button class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                <i class="fa-solid fa-filter text-slate-400"></i> Filter
            </button>
            <button class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                <i class="fa-solid fa-download text-slate-400"></i> Export
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Info Fasilitas</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Kategori</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Lokasi</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Kapasitas/Jumlah</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($fasilitas as $f)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            @if($f->gambar)
                                <img src="{{ asset('storage/' . $f->gambar) }}" alt="{{ $f->nama_fasilitas }}" class="h-12 w-12 rounded-lg object-cover border border-slate-200">
                            @else
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 border border-slate-200 text-slate-400">
                                    <i class="fa-regular fa-image text-xl"></i>
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('petugas.fasilitas.show', $f->id_fasilitas) }}" class="font-semibold text-slate-800 hover:text-blue-600">{{ $f->nama_fasilitas }}</a>
                                <div class="text-xs text-slate-500 mt-0.5">ID: FCL-{{ str_pad($f->id_fasilitas, 4, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">
                            {{ $f->kategori ?? 'Umum' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        {{ $f->lokasi ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $f->jumlah_unit ?? 0 }} {{ $f->satuan_kapasitas ?? 'Unit' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            @if(in_array(strtolower($f->status_fasilitas), ['tersedia', 'aktif']))
                                <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                <span class="font-medium text-green-700">Tersedia</span>
                            @elseif(strtolower($f->status_fasilitas) === 'digunakan')
                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                <span class="font-medium text-amber-700">Digunakan</span>
                            @elseif(in_array(strtolower($f->status_fasilitas), ['maintenance', 'perbaikan']))
                                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                <span class="font-medium text-red-700">Maintenance</span>
                            @else
                                <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                <span class="font-medium text-slate-600">{{ ucfirst($f->status_fasilitas) }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('petugas.fasilitas.edit', $f->id_fasilitas) }}" class="text-slate-400 hover:text-slate-700 transition" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('petugas.fasilitas.destroy', $f->id_fasilitas) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus fasilitas ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-red-600 transition" title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <i class="fa-solid fa-inbox text-4xl mb-3 text-slate-300"></i>
                        <p>Belum ada data fasilitas.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($fasilitas->hasPages())
    <div class="border-t border-slate-200 px-6 py-4">
        {{ $fasilitas->links() }}
    </div>
    @else
    <div class="border-t border-slate-200 px-6 py-4 text-sm text-slate-500">
        Menampilkan {{ $fasilitas->count() }} dari {{ $fasilitas->total() }} fasilitas
    </div>
    @endif
</div>

<!-- Additional Widgets Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Jadwal Pemeliharaan -->
    <div class="rounded-xl border border-[#ffe093] bg-[#fff5d6] p-6 shadow-sm relative overflow-hidden">
        <i class="fa-solid fa-wrench absolute -bottom-6 -right-6 text-8xl text-amber-500/10 rotate-12"></i>
        <h3 class="text-lg font-bold text-amber-800 mb-1">Jadwal Pemeliharaan</h3>
        <p class="text-sm text-amber-700/80 mb-5">Pengecekan rutin sarana prasarana minggu ini.</p>
        
        <div class="space-y-3 relative z-10">
            <div class="flex items-center justify-between rounded-lg bg-white/60 p-3 backdrop-blur-sm">
                <div class="flex items-center gap-3">
                    <div class="text-amber-600"><i class="fa-solid fa-router"></i></div>
                    <span class="font-semibold text-amber-900 text-sm">Update Firmware Router</span>
                </div>
                <span class="text-xs font-medium text-amber-800">Besok, 09:00</span>
            </div>
            <div class="flex items-center justify-between rounded-lg bg-white/60 p-3 backdrop-blur-sm">
                <div class="flex items-center gap-3">
                    <div class="text-amber-600"><i class="fa-solid fa-wind"></i></div>
                    <span class="font-semibold text-amber-900 text-sm">Servis AC Ruang Baca</span>
                </div>
                <span class="text-xs font-medium text-amber-800">Kamis, 14:00</span>
            </div>
        </div>
    </div>

    <!-- Permohonan Penggunaan -->
    <div class="rounded-xl border border-slate-200 bg-slate-100/50 p-6 shadow-sm">
        <h3 class="text-lg font-bold text-slate-800 mb-1">Permohonan Penggunaan</h3>
        <p class="text-sm text-slate-500 mb-5">Kelola antrean reservasi ruang diskusi dan auditorium.</p>
        
        <div class="flex items-center justify-between mt-auto pt-6">
            <div class="flex items-center gap-4">
                <div class="flex -space-x-2">
                    <div class="h-10 w-10 rounded-full border-2 border-white bg-slate-200"></div>
                    <div class="h-10 w-10 rounded-full border-2 border-white bg-slate-300"></div>
                    <div class="h-10 w-10 rounded-full border-2 border-white bg-slate-400"></div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-white bg-slate-800 text-xs font-medium text-white">+8</div>
                </div>
                <div class="text-sm">
                    <span class="block font-medium text-slate-800">Menunggu</span>
                    <span class="block text-slate-500">persetujuan</span>
                </div>
            </div>
            <a href="#" class="text-sm font-semibold text-slate-800 hover:text-blue-600">Lihat Semua</a>
        </div>
    </div>
</div>
@endsection
