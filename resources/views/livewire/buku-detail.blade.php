<div class="mx-auto max-w-[1180px] space-y-8">
    {{-- Breadcrumb --}}
    <nav class="flex text-sm text-slate-500 gap-2">
        <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-800 transition">Dashboard</a>
        <span>&rsaquo;</span>
        <a href="{{ route('petugas.koleksi') }}" class="hover:text-slate-800 transition">Buku</a>
        <span>&rsaquo;</span>
        <span class="text-slate-800 font-semibold">Detail Buku</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-serif text-3xl font-bold text-[#071426]">Detail Buku</h2>
            <p class="text-sm text-slate-500 mt-1">Informasi rinci mengenai koleksi buku dan riwayat sirkulasinya.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('petugas.buku.edit', $book->id_buku) }}"
               class="flex h-12 items-center justify-center gap-2 px-5 rounded-xl border border-slate-200 bg-white font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                <i class="fa-regular fa-pen-to-square"></i>
                Edit Buku
            </a>
            <a href="{{ route('petugas.buku.tambah-stok', $book->id_buku) }}"
               class="flex h-12 items-center justify-center gap-2 px-5 rounded-xl bg-[#142b3d] font-bold text-white transition hover:bg-[#1a3a52] shadow-sm">
                <i class="fa-solid fa-plus"></i>
                Tambah Stok
            </a>
        </div>
    </div>

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

    {{-- Book Info Section --}}
    <div class="grid gap-6 md:grid-cols-[280px_1fr]">
        {{-- Left side: Cover & Basic Stats --}}
        <div class="space-y-6">
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 flex flex-col items-center">
                <div class="h-64 w-48 overflow-hidden rounded-2xl bg-slate-100 border border-slate-200 shadow-md">
                    @if ($book->gambar_cover)
                        <img src="{{ Str::startsWith($book->gambar_cover, 'http') ? $book->gambar_cover : Storage::url($book->gambar_cover) }}"
                             alt="{{ $book->judul }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-slate-200 text-slate-400 text-5xl">
                            <i class="fa-solid fa-book"></i>
                        </div>
                    @endif
                </div>

                <div class="mt-5 w-full text-center">
                    @php
                        $statusText = 'Tersedia';
                        $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        
                        if ($book->eksemplar_count === 0) {
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
                    <span class="inline-block rounded-full border px-4 py-1 text-sm font-bold {{ $badgeClass }}">
                        {{ $statusText }}
                    </span>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm text-center">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Stok</p>
                    <p class="mt-2 text-2xl font-bold text-[#071426]">{{ $book->eksemplar_count }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm text-center">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Tersedia</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-600">{{ $book->eksemplar_tersedia_count }}</p>
                </div>
            </div>
        </div>

        {{-- Right side: Detailed Metadata --}}
        <div class="rounded-3xl bg-white p-8 shadow-sm border border-slate-100 space-y-6">
            <div>
                <span class="rounded-lg bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-600 border border-indigo-100">
                    {{ $book->kategori?->nama_kategori ?? '-' }}
                </span>
                <h3 class="mt-3 font-serif text-3xl font-bold text-[#071426] leading-tight">
                    {{ $book->judul }}
                </h3>
                <p class="mt-2 text-lg font-medium text-slate-600">
                    <i class="fa-regular fa-user mr-1.5 text-slate-400"></i>
                    {{ $book->penulis ?: 'Penulis belum diisi' }}
                </p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 border-y border-slate-100 py-6 text-sm">
                <div>
                    <p class="text-slate-400 font-semibold">Penerbit</p>
                    <p class="mt-1 font-bold text-slate-800">{{ $book->penerbit ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-semibold">Tahun Terbit</p>
                    <p class="mt-1 font-bold text-slate-800">{{ $book->tahun_terbit ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-semibold">ISBN</p>
                    <p class="mt-1 font-bold font-mono text-slate-800">{{ $book->isbn ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-semibold">Bahasa</p>
                    <p class="mt-1 font-bold text-slate-800">Indonesia</p>
                </div>
                <div>
                    <p class="text-slate-400 font-semibold">Lokasi Rak Utama</p>
                    <p class="mt-1 font-bold text-slate-800">
                        {{ $copies->first()?->lokasi_rak ?: 'Belum diatur' }}
                    </p>
                </div>
                <div>
                    <p class="text-slate-400 font-semibold">Edisi / Keterangan</p>
                    <p class="mt-1 font-bold text-slate-800">Edisi Standar</p>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-2">Abstrak / Sinopsis</h4>
                <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                    {{ $book->deskripsi ?: 'Tidak ada deskripsi atau sinopsis yang tersedia untuk buku ini.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Eksemplar List Section --}}
    <section id="eksemplar-list" class="rounded-3xl bg-white shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-slate-50/70 border-b border-slate-100 px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-[#071426]">
                <i class="fa-solid fa-boxes-stacked text-[#ffdc7c] mr-2"></i>
                Daftar Eksemplar Buku
            </h3>
            <span class="rounded-full bg-slate-200 px-3 py-0.5 text-xs font-bold text-slate-600">
                {{ $copies->count() }} Eksemplar
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-500 bg-slate-50/30">
                        <th class="px-6 py-4 w-12 text-center">NO</th>
                        <th class="px-6 py-4">KODE EKSEMPLAR</th>
                        <th class="px-6 py-4 text-center">STATUS</th>
                        <th class="px-6 py-4">SUMBER PEROLEHAN</th>
                        <th class="px-6 py-4">TANGGAL MASUK</th>
                        <th class="px-6 py-4">CATATAN</th>
                        <th class="px-6 py-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($copies as $index => $copy)
                        <tr class="hover:bg-slate-50/30 transition text-sm">
                            <td class="px-6 py-4 text-center font-semibold text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-slate-700">{{ $copy->kode_eksemplar }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $badge = 'bg-slate-50 text-slate-500 border-slate-200';
                                    if (strtolower($copy->status_eksemplar) === 'tersedia') {
                                        $badge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                    } elseif (strtolower($copy->status_eksemplar) === 'dipinjam') {
                                        $badge = 'bg-rose-50 text-rose-700 border-rose-200';
                                    } elseif (strtolower($copy->status_eksemplar) === 'rusak') {
                                        $badge = 'bg-amber-50 text-amber-700 border-amber-200';
                                    } elseif (strtolower($copy->status_eksemplar) === 'hilang') {
                                        $badge = 'bg-red-50 text-red-700 border-red-200';
                                    }
                                @endphp
                                <span class="inline-block rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $badge }}">
                                    {{ ucfirst($copy->status_eksemplar) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $copy->sumber_perolehan ?: '-' }}</td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $copy->tanggal_masuk ? $copy->tanggal_masuk->locale('id')->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 max-w-[200px] truncate" title="{{ $copy->catatan }}">
                                {{ $copy->catatan ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <select wire:change="updateCopyStatus({{ $copy->id_eksemplar_buku }}, $event.target.value)"
                                            @disabled($copy->has_active_borrowing)
                                            class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-xs font-semibold focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none disabled:opacity-50 transition">
                                        <option value="tersedia" @selected($copy->status_eksemplar === 'tersedia')>Set Tersedia</option>
                                        <option value="dipinjam" @selected($copy->status_eksemplar === 'dipinjam') disabled>Dipinjam</option>
                                        <option value="rusak" @selected($copy->status_eksemplar === 'rusak')>Set Rusak</option>
                                        <option value="hilang" @selected($copy->status_eksemplar === 'hilang')>Set Hilang</option>
                                        <option value="nonaktif" @selected($copy->status_eksemplar === 'nonaktif')>Set Nonaktif</option>
                                    </select>
                                    <button type="button" wire:click="deleteCopy({{ $copy->id_eksemplar_buku }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus eksemplar ini?"
                                            @disabled($copy->status_eksemplar === 'dipinjam')
                                            title="Hapus Eksemplar" class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-50 text-red-600 border border-red-200 transition hover:bg-red-100 disabled:opacity-50">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                Belum ada data eksemplar untuk buku ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Borrowing History Section --}}
    <section class="rounded-3xl bg-white shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-slate-50/70 border-b border-slate-100 px-6 py-4">
            <h3 class="text-lg font-bold text-[#071426]">
                <i class="fa-solid fa-clock-rotate-left text-[#ffdc7c] mr-2"></i>
                Riwayat Peminjaman
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-500 bg-slate-50/30">
                        <th class="px-6 py-4 w-12 text-center">NO</th>
                        <th class="px-6 py-4">ID PEMINJAMAN</th>
                        <th class="px-6 py-4">NAMA ANGGOTA</th>
                        <th class="px-6 py-4">TGL PINJAM</th>
                        <th class="px-6 py-4">BATAS KEMBALI</th>
                        <th class="px-6 py-4 text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($borrowingHistory as $index => $history)
                        <tr class="hover:bg-slate-50/30 transition text-sm">
                            <td class="px-6 py-4 text-center font-semibold text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-slate-700">
                                {{ $history->peminjaman?->kode_peminjaman ?: '-' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $history->peminjaman?->anggota?->nama_lengkap ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium">
                                {{ $history->peminjaman?->tanggal_diambil ? $history->peminjaman->tanggal_diambil->locale('id')->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium">
                                {{ $history->peminjaman?->tanggal_jatuh_tempo ? $history->peminjaman->tanggal_jatuh_tempo->locale('id')->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $status = strtolower($history->peminjaman?->status_peminjaman ?? '');
                                    $badge = 'bg-slate-50 text-slate-500 border-slate-200';
                                    if ($status === 'aktif') {
                                        $badge = 'bg-amber-50 text-amber-700 border-amber-200';
                                    } elseif ($status === 'selesai' || $status === 'kembali' || $status === 'dikembalikan') {
                                        $badge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                    } elseif ($status === 'terlambat') {
                                        $badge = 'bg-rose-50 text-rose-700 border-rose-200';
                                    }
                                @endphp
                                <span class="inline-block rounded-full border px-2.5 py-0.5 text-xs font-bold {{ $badge }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                Belum ada riwayat peminjaman untuk buku ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
