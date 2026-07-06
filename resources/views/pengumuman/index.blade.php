@extends('layouts.petugas')
@section('title', 'Daftar Pengumuman')

@section('content')
<div class="mx-auto max-w-[1180px] space-y-6">

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-emerald-800"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition>
            <i class="fa-solid fa-circle-check text-lg"></i>
            <p class="font-semibold">{{ session('success') }}</p>
            <button @click="show = false" class="ml-auto text-emerald-600 hover:text-emerald-800">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    {{-- Main Header & Actions --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-3xl font-bold font-serif text-[#0e1f30]">Daftar Pengumuman</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola informasi dan pengumuman untuk anggota perpustakaan.</p>
        </div>
        <div>
            <a href="{{ route('petugas.pengumuman.create') }}"
               class="inline-flex h-12 items-center gap-2 rounded-xl bg-[#0e1f30] hover:bg-[#1a344f] px-5 font-bold text-white transition shadow-sm">
                <i class="fa-solid fa-plus text-sm"></i>
                Tambah Pengumuman
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid gap-5 sm:grid-cols-3">
        {{-- Card 1: Total Pengumuman --}}
        <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm flex items-center gap-5">
            <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-500 text-2xl shadow-inner">
                <i class="fa-solid fa-bullhorn"></i>
            </span>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Total Pengumuman</p>
                <p class="mt-1 text-3xl font-extrabold text-[#0e1f30]">{{ number_format($stats['total']) }}</p>
            </div>
        </div>

        {{-- Card 2: Aktif --}}
        <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm flex items-center gap-5">
            <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-500 text-2xl shadow-inner">
                <i class="fa-solid fa-circle-check"></i>
            </span>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Aktif</p>
                <p class="mt-1 text-3xl font-extrabold text-[#0e1f30]">{{ number_format($stats['aktif']) }}</p>
            </div>
        </div>

        {{-- Card 3: Mendatang --}}
        <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm flex items-center gap-5">
            <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-500 text-2xl shadow-inner">
                <i class="fa-regular fa-clock"></i>
            </span>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Mendatang</p>
                <p class="mt-1 text-3xl font-extrabold text-[#0e1f30]">{{ number_format($stats['mendatang']) }}</p>
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="rounded-3xl border border-slate-100 bg-white shadow-sm overflow-hidden" x-data="{ deleteId: null, deleteTitle: '' }">
        {{-- Table Filter Bar --}}
        <div class="p-6 border-b border-slate-50 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-slate-50/50">
            <form method="GET" action="{{ route('petugas.pengumuman.index') }}" class="flex flex-wrap items-center gap-3 w-full">
                {{-- Search Bar --}}
                <div class="relative flex-1 min-w-[240px]">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search pengumuman..."
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-11 pr-4 text-sm focus:border-slate-300 focus:outline-none focus:ring-0">
                </div>

                {{-- Status Filter --}}
                <div class="w-48">
                    <select name="status" onchange="this.form.submit()"
                            class="h-10 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm focus:border-slate-300 focus:outline-none focus:ring-0">
                        <option value="">Semua Status</option>
                        <option value="Aktif" @selected(request('status') === 'Aktif')>Aktif</option>
                        <option value="Mendatang" @selected(request('status') === 'Mendatang')>Mendatang</option>
                        <option value="Selesai" @selected(request('status') === 'Selesai')>Selesai</option>
                        <option value="Draf" @selected(request('status') === 'Draf')>Draf</option>
                    </select>
                </div>

                @if (request()->hasAny(['search', 'status']))
                    <a href="{{ route('petugas.pengumuman.index') }}"
                       class="h-10 flex items-center gap-1.5 rounded-xl border border-slate-200 px-4 text-xs font-bold text-slate-500 hover:bg-slate-50 transition">
                        <i class="fa-solid fa-xmark"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Table Grid --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">Judul Pengumuman</th>
                        <th class="px-6 py-4">Tanggal Mulai</th>
                        <th class="px-6 py-4">Tanggal Selesai</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($pengumuman as $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('petugas.pengumuman.show', $item) }}" class="group">
                                    <h4 class="font-bold text-[#0e1f30] group-hover:text-[#1a344f] transition line-clamp-1">
                                        {{ $item->judul }}
                                    </h4>
                                    <p class="text-xs text-slate-400 mt-1 line-clamp-1 font-medium">
                                        {{ Str::limit(strip_tags($item->isi), 100) }}
                                    </p>
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-600">
                                {{ $item->tanggal_mulai ? $item->tanggal_mulai->locale('id')->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-600">
                                {{ $item->tanggal_selesai ? $item->tanggal_selesai->locale('id')->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $item->status_badge_class }}">
                                    {{ $item->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-1.5">
                                <a href="{{ route('petugas.pengumuman.edit', $item) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 text-slate-500 hover:bg-[#ffdc7c] hover:text-[#0e1f30] transition border border-slate-100">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <button type="button"
                                        @click="deleteId = @js($item->slug); deleteTitle = @js($item->judul)"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition border border-red-100">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-50 text-slate-400 text-xl shadow-inner">
                                        <i class="fa-solid fa-bullhorn"></i>
                                    </span>
                                    <h4 class="mt-4 font-bold text-slate-600">Belum ada pengumuman</h4>
                                    <p class="text-xs text-slate-400 mt-1">Klik tombol "+ Tambah Pengumuman" untuk membuat pengumuman baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination & Summary Footer --}}
        @if ($pengumuman->hasPages() || $pengumuman->total() > 0)
            <div class="px-6 py-4 border-t border-slate-50 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-slate-50/50">
                <span class="text-xs font-semibold text-slate-400">
                    Menampilkan {{ $pengumuman->firstItem() ?? 0 }}-{{ $pengumuman->lastItem() ?? 0 }} dari {{ $pengumuman->total() }} pengumuman
                </span>
                <div>
                    {{ $pengumuman->links() }}
                </div>
            </div>
        @endif

        {{-- Delete Confirmation Modal --}}
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm"
             x-show="deleteId"
             x-cloak
             x-transition>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-xl border border-slate-100"
                     @click.away="deleteId = null">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-500 text-xl shadow-inner">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-extrabold text-[#0e1f30]">Hapus Pengumuman</h3>
                    <p class="mt-2 text-sm text-slate-500">
                        Apakah Anda yakin ingin menghapus pengumuman <strong x-text="deleteTitle" class="text-slate-800"></strong>? Tindakan ini tidak dapat dibatalkan.
                    </p>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="deleteId = null"
                                class="h-11 rounded-xl border border-slate-200 px-4 text-sm font-bold text-slate-500 hover:bg-slate-50 transition">
                            Batal
                        </button>
                        <form :action="`{{ route('petugas.pengumuman.index') }}/${deleteId}`" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="h-11 rounded-xl bg-red-600 hover:bg-red-700 px-5 text-sm font-bold text-white transition shadow-md shadow-red-600/10">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
