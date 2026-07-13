@extends('layouts.petugas')

@section('title', 'Daftar Prestasi')

@section('content')
@php
    $statusLabels = [
        \App\Models\Prestasi::STATUS_PUBLISHED => 'Terbit',
        \App\Models\Prestasi::STATUS_DRAFT => 'Draft',
        \App\Models\Prestasi::STATUS_INACTIVE => 'Nonaktif',
    ];
@endphp

<div class="mx-auto max-w-[1180px] space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Daftar Prestasi</h2>
            <p class="mt-1 text-sm text-slate-500">Kelola pencapaian dan penghargaan perpustakaan.</p>
        </div>
        <a href="{{ route('petugas.prestasi.create') }}" class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-[#ffd15c] px-5 text-sm font-bold text-[#071426] shadow-sm transition hover:bg-[#f6c447]">
            <i class="fa-solid fa-plus"></i>
            Tambah Prestasi
        </a>
    </div>

    @if (session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-black uppercase tracking-wider text-slate-400">Total Prestasi</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="rounded-xl border border-emerald-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-black uppercase tracking-wider text-emerald-600">Terbit</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stats['terbit']) }}</p>
        </div>
        <div class="rounded-xl border border-amber-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-black uppercase tracking-wider text-amber-600">Draft</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stats['draft']) }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-black uppercase tracking-wider text-slate-500">Nonaktif</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stats['nonaktif']) }}</p>
        </div>
    </div>

    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <form method="GET" action="{{ route('petugas.prestasi.index') }}" class="grid gap-3 lg:grid-cols-[1fr_180px_160px_160px_auto]">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input name="search" value="{{ request('search') }}" placeholder="Cari judul, penyelenggara, nomor sertifikat..." class="h-11 w-full rounded-xl border-slate-200 bg-slate-50 pl-11 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                </div>
                <select name="tingkat" class="h-11 rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                    <option value="">Semua tingkat</option>
                    <option value="lokal" @selected(request('tingkat') === 'lokal')>Lokal</option>
                    <option value="nasional" @selected(request('tingkat') === 'nasional')>Nasional</option>
                    <option value="internasional" @selected(request('tingkat') === 'internasional')>Internasional</option>
                </select>
                <select name="tahun" class="h-11 rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                    <option value="">Semua tahun</option>
                    @foreach ($tahunList as $tahun)
                        <option value="{{ $tahun }}" @selected((string) request('tahun') === (string) $tahun)>{{ $tahun }}</option>
                    @endforeach
                </select>
                <select name="status" class="h-11 rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                    <option value="">Semua status</option>
                    @foreach ($statusLabels as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-[#0e1f30] px-5 text-sm font-bold text-white">
                        <i class="fa-solid fa-filter"></i>
                        Filter
                    </button>
                    @if (request()->hasAny(['search', 'tingkat', 'tahun', 'status']))
                        <a href="{{ route('petugas.prestasi.index') }}" class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 px-4 text-sm font-bold text-slate-600 hover:bg-slate-50">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Prestasi</th>
                        <th class="px-6 py-4">Tingkat</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Penyelenggara</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($prestasi as $item)
                        @php
                            $statusClass = match ($item->status_prestasi) {
                                \App\Models\Prestasi::STATUS_PUBLISHED => 'bg-emerald-50 text-emerald-700',
                                \App\Models\Prestasi::STATUS_DRAFT => 'bg-amber-50 text-amber-700',
                                default => 'bg-slate-100 text-slate-600',
                            };
                        @endphp
                        <tr class="text-sm">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    @if ($item->gambar)
                                        <img src="{{ Storage::url($item->gambar) }}" alt="{{ $item->judul_prestasi }}" class="h-12 w-12 rounded-lg border border-slate-200 object-cover">
                                    @else
                                        <span class="flex h-12 w-12 items-center justify-center rounded-lg border border-slate-200 bg-slate-100 text-slate-400">
                                            <i class="fa-solid fa-trophy"></i>
                                        </span>
                                    @endif
                                    <div>
                                        <a href="{{ route('petugas.prestasi.show', $item) }}" class="font-bold text-slate-900 hover:text-[#7c6312]">{{ $item->judul_prestasi }}</a>
                                        <p class="mt-0.5 max-w-xs truncate text-xs text-slate-500">{{ $item->nomor_sertifikat ?: 'Nomor sertifikat belum diisi' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold capitalize text-slate-700">{{ $item->tingkat_prestasi }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $item->tanggal_prestasi?->format('d M Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $item->penyelenggara ?: '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">{{ $statusLabels[$item->status_prestasi] ?? 'Nonaktif' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('petugas.prestasi.show', $item) }}" class="text-slate-500 hover:text-slate-900" aria-label="Lihat {{ $item->judul_prestasi }}">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a href="{{ route('petugas.prestasi.edit', $item) }}" class="text-slate-500 hover:text-slate-900" aria-label="Edit {{ $item->judul_prestasi }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form method="POST" action="{{ route('petugas.prestasi.destroy', $item) }}" onsubmit="return confirm('Yakin ingin menghapus prestasi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-500 hover:text-red-600" aria-label="Hapus {{ $item->judul_prestasi }}">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-xl text-slate-400">
                                        <i class="fa-solid fa-trophy"></i>
                                    </span>
                                    <h3 class="mt-4 font-bold text-slate-800">Belum ada prestasi</h3>
                                    <p class="mt-1 text-sm text-slate-500">Tambahkan prestasi pertama untuk mulai mengelola portofolio penghargaan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4 text-xs text-slate-500 md:flex-row md:items-center md:justify-between">
            <span>Menampilkan {{ $prestasi->count() }} dari {{ $prestasi->total() }} prestasi</span>
            {{ $prestasi->links() }}
        </div>
    </section>
</div>
@endsection
