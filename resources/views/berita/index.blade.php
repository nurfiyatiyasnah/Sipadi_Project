@extends('layouts.petugas')
@section('title', 'Kelola Berita')

@section('content')
<div class="mx-auto max-w-[1180px] space-y-8">

    {{-- Hero Header --}}
    <section class="overflow-hidden rounded-3xl bg-[#142b3d] text-white shadow-sm">
        <div class="grid gap-8 p-8 lg:grid-cols-[1fr_320px]">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-[#ffdc7c]">
                    <i class="fa-solid fa-newspaper"></i>
                    Manajemen Berita Perpustakaan
                </span>
                <h2 class="mt-5 font-serif text-4xl font-bold leading-tight">Kelola Berita</h2>
                <p class="mt-3 max-w-2xl text-lg text-slate-200">
                    Buat, kelola, dan terbitkan berita terkini perpustakaan untuk diakses oleh seluruh anggota dan masyarakat.
                </p>

                <div class="mt-8 grid max-w-2xl gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-300">Total Berita</p>
                        <p class="mt-2 text-2xl font-bold">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-300">Terbit</p>
                        <p class="mt-2 text-2xl font-bold text-emerald-400">{{ number_format($stats['terbit']) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-300">Draft</p>
                        <p class="mt-2 text-2xl font-bold text-amber-400">{{ number_format($stats['draft']) }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center rounded-3xl bg-white p-6 text-center text-[#071426]">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#ffdc7c] text-2xl">
                    <i class="fa-solid fa-pen-to-square"></i>
                </span>
                <h3 class="mt-4 text-lg font-bold">Buat Berita Baru</h3>
                <p class="mt-2 text-sm text-slate-500">Tulis dan terbitkan berita untuk perpustakaan.</p>
                <a href="{{ route('petugas.berita.create') }}"
                   class="mt-5 flex h-12 w-full items-center justify-center gap-3 rounded-xl bg-[#142b3d] font-bold text-white transition hover:bg-[#1a3a52]">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Berita Baru
                </a>
            </div>
        </div>
    </section>

    {{-- Filters --}}
    <section class="rounded-3xl bg-white p-6 shadow-sm">
        <form method="GET" action="{{ route('petugas.berita.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1">
                <label for="search" class="mb-2 block text-sm font-bold text-slate-600">Cari Berita</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input id="search" type="text" name="search" value="{{ request('search') }}"
                            placeholder="Ketik judul berita..."
                            class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c]">
                </div>
            </div>
            <div class="w-48">
                <label for="kategori" class="mb-2 block text-sm font-bold text-slate-600">Kategori</label>
                <select id="kategori" name="kategori"
                        class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c]">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoriList as $kat)
                        <option value="{{ $kat->id_kategori_berita }}" @selected(request('kategori') == $kat->id_kategori_berita)>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-40">
                <label for="status" class="mb-2 block text-sm font-bold text-slate-600">Status</label>
                <select id="status" name="status"
                        class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c]">
                    <option value="">Semua Status</option>
                    <option value="terbit" @selected(request('status') === 'terbit')>Terbit</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                </select>
            </div>
            <button type="submit"
                    class="flex h-12 items-center gap-2 rounded-xl bg-[#142b3d] px-6 font-bold text-white transition hover:bg-[#1a3a52]">
                <i class="fa-solid fa-filter"></i>
                Filter
            </button>
            @if (request()->hasAny(['search', 'kategori', 'status']))
                <a href="{{ route('petugas.berita.index') }}"
                    class="flex h-12 items-center gap-2 rounded-xl border border-slate-200 px-5 font-semibold text-slate-600 transition hover:bg-slate-50">
                    <i class="fa-solid fa-xmark"></i>
                    Reset
                </a>
            @endif
        </form>
    </section>

    {{-- Success Message --}}
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

    {{-- Berita Grid --}}
    <section>
        @if ($berita->isEmpty())
            <div class="flex flex-col items-center justify-center rounded-3xl bg-white px-6 py-16 shadow-sm">
                <span class="flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 text-4xl text-slate-400">
                    <i class="fa-regular fa-newspaper"></i>
                </span>
                <h3 class="mt-6 text-xl font-bold text-slate-700">Belum Ada Berita</h3>
                <p class="mt-2 text-slate-500">Mulai buat berita pertama untuk perpustakaan Anda.</p>
                <a href="{{ route('petugas.berita.create') }}"
                   class="mt-6 flex h-12 items-center gap-3 rounded-xl bg-[#ffdc7c] px-6 font-bold text-[#071426] transition hover:bg-[#f5d06a]">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Berita Baru
                </a>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2" x-data="{ deleteId: null, deleteTitle: '', deleteImage: '' }">
                @foreach ($berita as $item)
                    <article class="group overflow-hidden rounded-3xl bg-white shadow-sm transition hover:shadow-md">
                        {{-- Thumbnail --}}
                        <div class="relative h-52 overflow-hidden bg-slate-100">
                            @if ($item->gambar)
                                <img src="{{ Storage::url($item->gambar) }}" alt="{{ $item->judul }}"
                                     class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#142b3d] to-[#1f4565]">
                                    <i class="fa-solid fa-image text-5xl text-white/30"></i>
                                </div>
                            @endif

                            {{-- Status Badge --}}
                            <span class="absolute left-4 top-4 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider
                                {{ $item->status_berita === \App\Models\Berita::STATUS_PUBLISHED
                                    ? 'bg-emerald-500 text-white'
                                    : 'bg-amber-400 text-[#071426]' }}">
                                {{ $item->status_berita === \App\Models\Berita::STATUS_PUBLISHED ? 'TERBIT' : 'DRAFT' }}
                            </span>
                        </div>

                        {{-- Content --}}
                        <div class="p-6">
                            <div class="flex items-center gap-3 text-sm text-slate-500">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar"></i>
                                    {{ $item->tanggal_terbit ? $item->tanggal_terbit->locale('id')->translatedFormat('d M Y') : 'Belum terbit' }}
                                </span>
                                @if ($item->kategoriBerita)
                                    <span class="rounded-full bg-slate-100 px-3 py-0.5 text-xs font-semibold text-slate-600">
                                        {{ $item->kategoriBerita->nama_kategori }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="mt-3 text-lg font-bold leading-snug text-[#071426]">
                                {{ $item->judul }}
                            </h3>

                            <p class="mt-2 line-clamp-2 text-sm text-slate-600">
                                {{ Str::limit(strip_tags($item->isi), 120) }}
                            </p>

                            <div class="mt-5 flex items-center gap-2 border-t border-slate-100 pt-4">
                                @if ($item->status_berita === \App\Models\Berita::STATUS_DRAFT)
                                    <form method="POST" action="{{ route('petugas.berita.publish', $item) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="flex h-9 items-center gap-2 rounded-lg bg-emerald-50 px-4 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                                            <i class="fa-solid fa-paper-plane text-xs"></i>
                                            Terbitkan
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('petugas.berita.edit', $item) }}"
                                   class="flex h-9 items-center gap-2 rounded-lg bg-slate-50 px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    Edit
                                </a>
                                <button type="button"
                                        @click="deleteId = {{ $item->id_berita }}; deleteTitle = @js($item->judul); deleteImage = @js($item->gambar ? Storage::url($item->gambar) : '')"
                                        class="ml-auto flex h-9 items-center gap-2 rounded-lg bg-red-50 px-4 text-sm font-semibold text-red-600 transition hover:bg-red-100">
                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach

                {{-- Delete Modal --}}
                <div x-show="deleteId !== null" x-cloak x-transition.opacity
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                     @keydown.escape.window="deleteId = null">
                    <div class="w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl"
                         @click.outside="deleteId = null" x-transition.scale.95>
                        <div class="bg-red-50 px-8 py-6 text-center">
                            <span class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-3xl text-red-500">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </span>
                            <h3 class="mt-4 text-xl font-bold text-[#071426]">Hapus Berita?</h3>
                            <p class="mt-2 text-sm text-slate-600">Tindakan ini tidak dapat dibatalkan. Berita berikut akan dihapus secara permanen:</p>
                        </div>

                        <div class="px-8 py-5">
                            <div class="flex items-center gap-4 rounded-2xl border border-slate-100 p-4">
                                <template x-if="deleteImage">
                                    <img :src="deleteImage" class="h-16 w-16 rounded-xl object-cover" alt="Preview">
                                </template>
                                <template x-if="!deleteImage">
                                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-xl bg-slate-100">
                                        <i class="fa-solid fa-image text-xl text-slate-400"></i>
                                    </div>
                                </template>
                                <p class="font-bold text-[#071426]" x-text="deleteTitle"></p>
                            </div>
                        </div>

                        <div class="flex gap-3 border-t border-slate-100 px-8 py-5">
                            <button @click="deleteId = null"
                                    class="flex h-12 flex-1 items-center justify-center rounded-xl border border-slate-200 font-bold text-slate-600 transition hover:bg-slate-50">
                                Batal
                            </button>
                            <form :action="@js(route('petugas.berita.destroy', ['berita' => '__BERITA_ID__'])).replace('__BERITA_ID__', deleteId)" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-red-600 font-bold text-white transition hover:bg-red-700">
                                    <i class="fa-regular fa-trash-can"></i>
                                    Hapus Berita
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $berita->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
