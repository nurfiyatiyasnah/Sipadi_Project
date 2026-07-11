@extends('layouts.petugas')

@section('title', 'Daftar Layanan')

@section('content')
<div class="mx-auto max-w-[1180px] space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Daftar Layanan</h2>
            <p class="mt-1 text-sm text-slate-500">Manajemen layanan publik dan internal perpustakaan SIPADI.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <button type="button" class="inline-flex h-11 items-center gap-2 rounded-xl bg-slate-100 px-5 text-sm font-bold text-slate-700 transition hover:bg-slate-200">
                <i class="fa-solid fa-download"></i>
                Export PDF
            </button>
            <a href="{{ route('petugas.layanan.create') }}" class="inline-flex h-11 items-center gap-2 rounded-xl bg-[#ffd15c] px-5 text-sm font-bold text-[#071426] shadow-sm transition hover:bg-[#f6c447]">
                <i class="fa-solid fa-plus"></i>
                Layanan Baru
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    @livewire('layanan-stats')

    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <form method="GET" action="{{ route('petugas.layanan.index') }}" class="flex flex-col gap-3 md:flex-row">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input name="search" value="{{ request('search') }}" placeholder="Cari layanan perpustakaan..." class="h-11 w-full rounded-xl border-slate-200 bg-slate-50 pl-11 pr-11 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                    @if(request('search') || request('status'))
                        <a href="{{ route('petugas.layanan.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition" title="Reset Pencarian">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>
                <select name="status" class="h-11 rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                    <option value="">Semua status</option>
                    <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                    <option value="review" @selected(request('status') === 'review')>Perlu Review</option>
                    <option value="maintenance" @selected(request('status') === 'maintenance')>Maintenance</option>
                    <option value="nonaktif" @selected(request('status') === 'nonaktif')>Non-Aktif</option>
                </select>
                <button type="submit" class="h-11 rounded-xl bg-[#0e1f30] px-5 text-sm font-bold text-white">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Nama Layanan</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Jam Operasional</th>
                        <th class="px-6 py-4">PIC</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($layanan as $item)
                        @php
                            $statusClass = match ($item->status_layanan) {
                                'aktif' => 'bg-emerald-50 text-emerald-700',
                                'maintenance' => 'bg-orange-50 text-orange-700',
                                'review' => 'bg-blue-50 text-blue-700',
                                default => 'bg-slate-100 text-slate-600',
                            };
                        @endphp
                        <tr class="text-sm">
                            <td class="px-6 py-4">
                                <a href="{{ route('petugas.layanan.show', $item) }}" class="font-bold text-slate-900 hover:text-[#7c6312]">{{ $item->nama_layanan }}</a>
                                <p class="mt-0.5 max-w-xs truncate text-xs text-slate-500">{{ $item->deskripsi ?: 'Layanan perpustakaan SIPADI' }}</p>
                            </td>
                            <td class="px-6 py-4 text-slate-600">Publik</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-bold capitalize {{ $statusClass }}">{{ str_replace('_', ' ', $item->status_layanan ?? 'nonaktif') }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $item->jam_layanan ?: '08:00 - 16:00' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $item->createdBy?->nama_petugas ?? 'Admin Perpus' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('petugas.layanan.edit', $item) }}" class="text-slate-500 hover:text-slate-900" aria-label="Edit {{ $item->nama_layanan }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <div x-data="{ showDeleteModal: false }" class="inline">
                                        <button @click="showDeleteModal = true" type="button" class="text-slate-500 hover:text-red-600 transition" aria-label="Hapus {{ $item->nama_layanan }}" title="Hapus">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                        
                                        <!-- Delete Modal -->
                                        <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <!-- Background backdrop -->
                                            <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>

                                            <!-- Modal panel -->
                                            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                                                <div x-show="showDeleteModal" 
                                                     x-transition:enter="ease-out duration-300" 
                                                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                                     x-transition:leave="ease-in duration-200" 
                                                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                     @click.away="showDeleteModal = false"
                                                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg z-50">
                                                     
                                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                                        <div class="sm:flex sm:items-start">
                                                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                                                            </div>
                                                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                                                <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">Hapus Layanan</h3>
                                                                <div class="mt-3 text-sm text-slate-500 space-y-2">
                                                                    <p>Apakah Anda yakin ingin menghapus layanan <strong>{{ $item->nama_layanan }}</strong>?</p>
                                                                    <p>Data yang terkait dengan layanan ini akan dihapus permanen dan tidak dapat dikembalikan.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                                        <form action="{{ route('petugas.layanan.destroy', $item) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition">
                                                                Ya, Hapus
                                                            </button>
                                                        </form>
                                                        <button @click="showDeleteModal = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition">
                                                            Batal
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-xl text-slate-400">
                                        <i class="fa-solid fa-handshake-angle"></i>
                                    </span>
                                    <h3 class="mt-4 font-bold text-slate-800">Belum ada layanan</h3>
                                    <p class="mt-1 text-sm text-slate-500">Tambahkan layanan pertama untuk menampilkan data seperti mockup.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4 text-xs text-slate-500 md:flex-row md:items-center md:justify-between">
            <span>Menampilkan {{ $layanan->count() }} dari {{ $layanan->total() }} layanan</span>
            {{ $layanan->links() }}
        </div>
    </section>
</div>
@endsection
