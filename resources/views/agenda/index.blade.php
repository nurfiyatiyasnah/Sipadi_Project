@extends('layouts.petugas')
 
@section('title', 'Daftar Agenda')
 
@section('content')
<div class="mx-auto max-w-[1180px] space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-serif text-3xl font-bold leading-tight text-slate-900">Daftar Agenda</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola jadwal kegiatan dan acara perpustakaan.</p>
        </div>
        <div>
            <a href="{{ route('petugas.agenda.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-[#8c741c] hover:bg-[#725e17] px-5 py-3 text-sm font-bold text-white shadow-sm transition">
                <i class="fa-solid fa-plus"></i>
                Tambah Agenda Baru
            </a>
        </div>
    </div>
 
    {{-- Success Notification --}}
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-emerald-800"
             x-data="{ show: true }" x-show="show" x-transition>
            <i class="fa-solid fa-circle-check text-lg"></i>
            <p class="font-semibold text-sm">{{ session('success') }}</p>
            <button @click="show = false" class="ml-auto text-emerald-600 hover:text-emerald-800">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif
 
    {{-- Search & Filter Section --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('petugas.agenda.index') }}" class="flex flex-wrap items-center gap-4">
            {{-- Search input --}}
            <div class="flex-1 min-w-[280px]">
                <div class="relative">
                    <span class="absolute inset-y-0 left-4 flex items-center text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari judul agenda..."
                           class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm outline-none transition focus:border-slate-300 focus:bg-white placeholder:text-slate-400">
                </div>
            </div>
 
            {{-- Status filter --}}
            <div class="w-48">
                <select name="status" onchange="this.form.submit()"
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm outline-none transition focus:border-slate-300 focus:bg-white cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="terbit" @selected(request('status') === 'terbit')>Dipublikasi</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                </select>
            </div>
 
            <button type="submit"
                    class="flex h-12 items-center gap-2 rounded-2xl border border-slate-200 px-6 font-bold text-slate-700 hover:bg-slate-50 transition">
                <i class="fa-solid fa-sliders text-sm text-slate-500"></i>
                Filter
            </button>
 
            @if (request()->hasAny(['search', 'status']))
                <a href="{{ route('petugas.agenda.index') }}"
                   class="flex h-12 items-center gap-2 rounded-2xl border border-red-200 px-5 font-semibold text-red-600 hover:bg-red-50 transition">
                    <i class="fa-solid fa-xmark"></i>
                    Reset
                </a>
            @endif
        </form>
    </div>
 
    {{-- Agenda Table --}}
    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100" x-data="{ deleteId: null, deleteTitle: '' }">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Judul Agenda</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Tanggal</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Lokasi</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Status Publikasi</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($events as $event)
                        <tr class="hover:bg-slate-50/30 transition">
                            {{-- Judul & Kategori --}}
                            <td class="py-5 px-6">
                                <div class="font-bold text-slate-800 text-sm md:text-base">{{ $event->judul_event }}</div>
                                <div class="text-xs text-slate-400 mt-1 font-medium">{{ $event->kategori ?? 'Kategori tidak ditentukan' }}</div>
                            </td>
                            {{-- Tanggal & Waktu --}}
                            <td class="py-5 px-6 text-sm text-slate-600 leading-relaxed">
                                <div class="font-semibold text-slate-700">
                                    {{ $event->tanggal_mulai ? $event->tanggal_mulai->locale('id')->translatedFormat('d Nov Y') : '-' }}
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    {{ $event->jam_mulai ? substr($event->jam_mulai, 0, 5) : '--:--' }} 
                                    @if($event->jam_selesai)
                                        - {{ substr($event->jam_selesai, 0, 5) }}
                                    @endif
                                    WIB
                                </div>
                            </td>
                            {{-- Lokasi --}}
                            <td class="py-5 px-6 text-sm text-slate-600 font-medium">
                                @if(filter_var($event->lokasi, FILTER_VALIDATE_URL))
                                    <a href="{{ $event->lokasi }}" target="_blank" class="text-[#8c741c] hover:underline inline-flex items-center gap-1">
                                        Tautan Peta <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </a>
                                @else
                                    {{ $event->lokasi ?? '-' }}
                                @endif
                            </td>
                            {{-- Status Publikasi Badge --}}
                            <td class="py-5 px-6">
                                @if ($event->status_event === 'terbit')
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                        Dipublikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 border border-slate-200">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            {{-- Aksi --}}
                            <td class="py-5 px-6 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    {{-- Detail --}}
                                    <a href="{{ route('petugas.agenda.show', $event) }}"
                                       class="text-slate-400 hover:text-slate-700 transition" title="Lihat Detail">
                                        <i class="fa-regular fa-eye text-lg"></i>
                                    </a>
                                    {{-- Edit --}}
                                    <a href="{{ route('petugas.agenda.edit', $event) }}"
                                       class="text-slate-400 hover:text-slate-700 transition" title="Edit Agenda">
                                        <i class="fa-regular fa-pen-to-square text-lg"></i>
                                    </a>
                                    {{-- Hapus --}}
                                    <button type="button"
                                            @click="deleteId = {{ $event->id_event }}; deleteTitle = @js($event->judul_event)"
                                            class="text-slate-400 hover:text-red-600 transition" title="Hapus Agenda">
                                        <i class="fa-regular fa-trash-can text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="text-4xl"><i class="fa-regular fa-calendar-times"></i></div>
                                <p class="mt-3 text-sm font-medium">Tidak ada data agenda ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
 
        {{-- Pagination --}}
        @if($events->hasPages() || $events->total() > 0)
            <div class="bg-slate-50/50 border-t border-slate-100 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs md:text-sm text-slate-500 font-medium">
                <div>
                    Menampilkan {{ $events->firstItem() ?? 0 }} hingga {{ $events->lastItem() ?? 0 }} dari {{ $events->total() }} agenda
                </div>
                <div>
                    {{ $events->links() }}
                </div>
            </div>
        @endif
 
        {{-- Delete Confirmation Modal --}}
        <div x-show="deleteId !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" x-transition>
            <div class="bg-white rounded-3xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-100" @click.outside="deleteId = null">
                <div class="p-6 text-center space-y-4">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500 text-2xl">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </span>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Hapus Agenda?</h3>
                        <p class="text-sm text-slate-500 mt-1">Apakah Anda yakin ingin menghapus agenda <strong class="text-slate-800" x-text="deleteTitle"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
                <div class="flex border-t border-slate-100">
                    <button type="button" @click="deleteId = null"
                            class="flex-1 py-4 font-semibold text-slate-600 hover:bg-slate-50 transition border-r border-slate-100">
                        Batal
                    </button>
                    <form :action="'{{ route('petugas.agenda.destroy', '__ID__') }}'.replace('__ID__', deleteId)" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full h-full py-4 font-semibold text-red-600 hover:bg-red-50 transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
