<div class="mx-auto max-w-[1180px] space-y-8">
    {{-- Hero Header --}}
    <section class="overflow-hidden rounded-3xl bg-[#142b3d] text-white shadow-sm">
        <div class="grid gap-8 p-8 lg:grid-cols-[1fr_320px]">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-[#ffdc7c]">
                    <i class="fa-solid fa-book"></i>
                    Manajemen Koleksi Buku
                </span>
                <h2 class="mt-5 font-serif text-4xl font-bold leading-tight">Koleksi Buku</h2>
                <p class="mt-3 max-w-2xl text-lg text-slate-200">
                    Kelola data koleksi buku, stok, kategori, dan status ketersediaan buku di perpustakaan SIPADI.
                </p>

                <div class="mt-8 grid max-w-2xl gap-4 sm:grid-cols-4">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-300">Total Judul</p>
                        <p class="mt-2 text-2xl font-bold">{{ number_format($stats['judul']) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-300">Total Eksemplar</p>
                        <p class="mt-2 text-2xl font-bold text-amber-400">{{ number_format($stats['eksemplar']) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-300">Sedang Dipinjam</p>
                        <p class="mt-2 text-2xl font-bold text-rose-400">{{ number_format($stats['dipinjam']) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-300">Tersedia</p>
                        <p class="mt-2 text-2xl font-bold text-emerald-400">{{ number_format($stats['tersedia']) }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center rounded-3xl bg-white p-6 text-center text-[#071426]">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#ffdc7c] text-2xl">
                    <i class="fa-solid fa-book-medical"></i>
                </span>
                <h3 class="mt-4 text-lg font-bold">Tambah Buku Baru</h3>
                <p class="mt-2 text-sm text-slate-500">Daftarkan koleksi buku baru ke dalam sistem.</p>
                <a href="{{ route('petugas.buku.create') }}"
                   class="mt-5 flex h-12 w-full items-center justify-center gap-3 rounded-xl bg-[#142b3d] font-bold text-white transition hover:bg-[#1a3a52]">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Buku
                </a>
            </div>
        </div>
    </section>

    {{-- Filters --}}
    <section class="rounded-3xl bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-end gap-4">
            <div class="flex-1">
                <label for="search" class="mb-2 block text-sm font-bold text-slate-600">Cari Buku</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input id="search" type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Cari judul, penulis, atau kode buku..."
                            class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none">
                </div>
            </div>
            <div class="w-48">
                <label for="kategori" class="mb-2 block text-sm font-bold text-slate-600">Kategori</label>
                <select id="kategori" wire:model.live="kategori"
                        class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id_kategori }}">
                            {{ $cat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-48">
                <label for="status" class="mb-2 block text-sm font-bold text-slate-600">Status Katalog</label>
                <select id="status" wire:model.live="status"
                        class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <a href="{{ route('petugas.koleksi.export', ['search' => $search, 'kategori' => $kategori, 'status' => $status]) }}" 
               class="flex h-12 items-center gap-2 rounded-xl border border-slate-200 px-5 font-semibold text-slate-600 transition hover:bg-slate-50">
                <i class="fa-solid fa-file-csv"></i>
                Ekspor CSV
            </a>
        </div>
    </section>

    {{-- Alerts --}}
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
    @if (session('error'))
        <div class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-6 py-4 text-rose-800"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)" x-transition>
            <i class="fa-solid fa-circle-xmark text-lg"></i>
            <p class="font-semibold">{{ session('error') }}</p>
            <button @click="show = false" class="ml-auto text-rose-600 hover:text-rose-800">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    {{-- Buku Table --}}
    <section class="overflow-hidden rounded-3xl bg-white shadow-sm border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-4 text-center w-16">NO</th>
                        <th class="px-6 py-4">INFO BUKU (JUDUL & KODE)</th>
                        <th class="px-6 py-4">PENULIS</th>
                        <th class="px-6 py-4">KATEGORI</th>
                        <th class="px-6 py-4 text-center">EKSEMPLAR</th>
                        <th class="px-6 py-4 text-center">TERSEDIA</th>
                        <th class="px-6 py-4 text-center">STATUS</th>
                        <th class="px-6 py-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($books as $index => $book)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 text-center text-sm font-semibold text-slate-400">
                                {{ ($books->currentPage() - 1) * $books->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-16 w-12 shrink-0 overflow-hidden rounded-lg bg-slate-100 border border-slate-200">
                                        @if ($book->gambar_cover)
                                            <img src="{{ Str::startsWith($book->gambar_cover, 'http') ? $book->gambar_cover : Storage::url($book->gambar_cover) }}"
                                                 alt="{{ $book->judul }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-slate-200 text-slate-400">
                                                <i class="fa-solid fa-book"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-[#071426] line-clamp-1 hover:text-indigo-600">
                                            <a href="{{ route('petugas.buku.show', $book->id_buku) }}">{{ $book->judul }}</a>
                                        </p>
                                        <p class="mt-1 text-xs font-mono text-slate-500">
                                            {{ $book->kode_buku }} | ISBN: {{ $book->isbn ?: '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-600">
                                {{ $book->penulis ?: 'Penulis belum diisi' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded-lg bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                    {{ $book->kategori?->nama_kategori ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-bold text-slate-700">
                                {{ $book->eksemplar_count }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-bold {{ $book->eksemplar_tersedia_count > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $book->eksemplar_tersedia_count }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusText = 'Tersedia';
                                    $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                    
                                    if (strtolower($book->status_katalog ?? '') === 'nonaktif') {
                                        $statusText = 'Nonaktif';
                                        $badgeClass = 'bg-slate-100 text-slate-600 border-slate-300';
                                    } elseif ($book->eksemplar_count === 0) {
                                        $statusText = 'Stok Kosong';
                                        $badgeClass = 'bg-slate-50 text-slate-500 border-slate-200';
                                    } elseif ($book->eksemplar_tersedia_count === 0) {
                                        $statusText = 'Dipinjam Semua';
                                        $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                    } elseif ($book->eksemplar_tersedia_count < 3) {
                                        $statusText = 'Stok Menipis';
                                        $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                    }
                                @endphp
                                <span class="inline-block rounded-full border px-3 py-0.5 text-xs font-bold {{ $badgeClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('petugas.buku.show', $book->id_buku) }}"
                                       title="Detail Buku" class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition hover:bg-slate-200">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a href="{{ route('petugas.buku.edit', $book->id_buku) }}"
                                       title="Edit Buku" class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600 border border-amber-200 transition hover:bg-amber-100">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <a href="{{ route('petugas.buku.tambah-stok', $book->id_buku) }}"
                                       title="Tambah Stok" class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-200 transition hover:bg-blue-100">
                                        <i class="fa-solid fa-boxes-stacked"></i>
                                    </a>
                                    <button type="button" wire:click="deleteBook({{ $book->id_buku }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus/menonaktifkan buku ini?"
                                            title="Hapus / Nonaktifkan" class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-50 text-red-600 border border-red-200 transition hover:bg-red-100">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <i class="fa-solid fa-book-open text-4xl text-slate-300"></i>
                                    <p class="font-semibold text-slate-500">Tidak ada data buku ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($books->hasPages())
            <div class="border-t border-slate-100 px-6 py-4 bg-slate-50/50">
                {{ $books->links() }}
            </div>
        @endif
    </section>

    <div class="flex items-center justify-between text-xs text-slate-400 px-4">
        <p>Persentase ketersediaan seluruh eksemplar: {{ $stats['persen'] }}%</p>
        @if (!empty($status) || !empty($kategori))
            <p>Filter aktif: Kategori: {{ $kategori ?: 'Semua' }} | Status: {{ $status ?: 'Semua' }}</p>
        @endif
    </div>
</div>
