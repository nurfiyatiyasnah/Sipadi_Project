@extends('layouts.petugas')
@section('title', 'Struktur Kepegawaian')

@section('content')
<div class="mx-auto max-w-[1180px] space-y-8">

    {{-- Hero Header --}}
    <section class="overflow-hidden rounded-3xl bg-[#142b3d] text-white shadow-sm">
        <div class="grid gap-8 p-8 lg:grid-cols-[1fr_320px]">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-[#ffdc7c]">
                    <i class="fa-solid fa-sitemap"></i>
                    Manajemen Struktur Kepegawaian
                </span>
                <h2 class="mt-5 font-serif text-4xl font-bold leading-tight">Struktur Kepegawaian</h2>
                <p class="mt-3 max-w-2xl text-lg text-slate-200">
                    Kelola daftar anggota dan jabatan dalam struktur kepegawaian perpustakaan.
                </p>

                <div class="mt-8 grid max-w-2xl gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-300">Total Anggota</p>
                        <p class="mt-2 text-2xl font-bold">{{ $kepegawaian->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center rounded-3xl bg-white p-6 text-center text-[#071426]">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#ffdc7c] text-2xl">
                    <i class="fa-solid fa-user-plus"></i>
                </span>
                <h3 class="mt-4 text-lg font-bold">Tambah Anggota</h3>
                <p class="mt-2 text-sm text-slate-500">Tambahkan anggota baru ke struktur kepegawaian.</p>
                <a href="{{ route('petugas.kepegawaian.create') }}"
                   class="mt-5 flex h-12 w-full items-center justify-center gap-3 rounded-xl bg-[#142b3d] font-bold text-white transition hover:bg-[#1a3a52]">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Anggota
                </a>
            </div>
        </div>
    </section>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="flex items-center gap-4 rounded-2xl bg-emerald-50 p-4 text-emerald-700">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                <i class="fa-solid fa-check"></i>
            </span>
            <div>
                <p class="font-bold">Berhasil!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Table list --}}
    <section class="rounded-3xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-bold">Foto</th>
                        <th class="px-6 py-4 font-bold">Nama</th>
                        <th class="px-6 py-4 font-bold">Jabatan</th>
                        <th class="px-6 py-4 font-bold">Urutan</th>
                        <th class="px-6 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($kepegawaian as $item)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-6 py-4">
                                @if ($item->foto)
                                    <img src="{{ Storage::url($item->foto) }}" alt="{{ $item->nama }}" class="h-12 w-12 rounded-lg object-cover">
                                @else
                                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-200 text-slate-400">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-[#071426]">{{ $item->nama }}</td>
                            <td class="px-6 py-4">{{ $item->jabatan }}</td>
                            <td class="px-6 py-4">{{ $item->urutan }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('petugas.kepegawaian.edit', $item) }}" class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition hover:bg-blue-100 hover:text-blue-600">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('petugas.kepegawaian.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition hover:bg-red-100 hover:text-red-600">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                Belum ada data anggota struktur kepegawaian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($kepegawaian->hasPages())
            <div class="border-t border-slate-200 p-6">
                {{ $kepegawaian->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
