<div class="mx-auto max-w-[1180px] space-y-6">
    {{-- Breadcrumb --}}
    <nav class="flex text-sm text-slate-500 gap-2">
        <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-800 transition">Dashboard</a>
        <span>&rsaquo;</span>
        <a href="{{ route('petugas.koleksi') }}" class="hover:text-slate-800 transition">Buku</a>
        <span>&rsaquo;</span>
        <a href="{{ route('petugas.buku.show', $book->id_buku) }}" class="hover:text-slate-800 transition">Detail Buku</a>
        <span>&rsaquo;</span>
        <span class="text-slate-800 font-semibold">Tambah Stok</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 text-2xl shadow-sm">
            <i class="fa-solid fa-boxes-stacked"></i>
        </span>
        <div>
            <h2 class="font-serif text-3xl font-bold text-[#071426]">Tambah Stok Buku</h2>
            <p class="text-sm text-slate-500 mt-1">Tambahkan jumlah ketersediaan buku fisik di perpustakaan.</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[320px_1fr]">
        {{-- Left side: Book Info Card --}}
        <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 space-y-6 flex flex-col items-center">
            <h3 class="text-md font-bold text-slate-400 uppercase tracking-wider self-start">Informasi Buku</h3>

            <div class="h-48 w-36 overflow-hidden rounded-2xl bg-slate-100 border border-slate-200 shadow-md">
                @if ($book->gambar_cover)
                    <img src="{{ Str::startsWith($book->gambar_cover, 'http') ? $book->gambar_cover : Storage::url($book->gambar_cover) }}"
                         alt="{{ $book->judul }}" class="h-full w-full object-cover">
                @else
                    <div class="flex h-full w-full items-center justify-center bg-slate-200 text-slate-400 text-3xl">
                        <i class="fa-solid fa-book"></i>
                    </div>
                @endif
            </div>

            <div class="text-center space-y-1">
                <h4 class="font-bold text-lg text-[#071426] line-clamp-2">{{ $book->judul }}</h4>
                <p class="text-sm font-medium text-slate-500">{{ $book->penulis }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 w-full border-t border-slate-100 pt-4 text-xs">
                <div>
                    <p class="text-slate-400 font-semibold">ISBN</p>
                    <p class="mt-0.5 font-bold font-mono text-slate-700">{{ $book->isbn ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-semibold">Tahun Terbit</p>
                    <p class="mt-0.5 font-bold text-slate-700">{{ $book->tahun_terbit ?: '-' }}</p>
                </div>
            </div>

            <div class="w-full rounded-2xl bg-slate-50 p-4 text-center border border-slate-100/60">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Stok Saat Ini</p>
                <p class="mt-1.5 text-xl font-bold text-[#071426]">{{ $book->eksemplar_count }} <span class="text-sm font-semibold text-slate-500">Eks</span></p>
            </div>
        </div>

        {{-- Right side: Form Penambahan Stok --}}
        <div class="rounded-3xl bg-white p-8 shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-lg font-bold text-[#071426] border-b border-slate-100 pb-3">
                Form Penambahan Stok
            </h3>

            {{-- Warning Box --}}
            <div class="flex gap-3 rounded-2xl border border-amber-200 bg-amber-50/50 p-4 text-amber-800 text-sm leading-relaxed">
                <i class="fa-solid fa-circle-info text-lg mt-0.5 shrink-0 text-amber-600"></i>
                <div>
                    <p class="font-bold">Peringatan Input</p>
                    <p class="mt-0.5 text-amber-900/80">Pastikan jumlah fisik buku yang diterima sesuai dengan angka yang dimasukkan. Tindakan ini akan langsung membuat data eksemplar baru dan memperbarui total inventaris sistem.</p>
                </div>
            </div>

            <form wire:submit.prevent="save" class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    {{-- Jumlah Stok Tambahan --}}
                    <div>
                        <label for="jumlah_stok_tambahan" class="block text-sm font-bold text-slate-700">Jumlah Stok Tambahan <span class="text-rose-500">*</span></label>
                        <input type="number" id="jumlah_stok_tambahan" wire:model="jumlah_stok_tambahan" min="1" max="500"
                               class="mt-2 h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none">
                        @error('jumlah_stok_tambahan') <span class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</span> @enderror
                    </div>

                    {{-- Sumber Perolehan --}}
                    <div>
                        <label for="sumber_perolehan" class="block text-sm font-bold text-slate-700">Sumber Perolehan <span class="text-rose-500">*</span></label>
                        <select id="sumber_perolehan" wire:model="sumber_perolehan"
                                class="mt-2 h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none">
                            <option value="">Pilih sumber...</option>
                            <option value="Pembelian">Pembelian</option>
                            <option value="Hibah / Sumbangan">Hibah / Sumbangan</option>
                            <option value="Dinas Pendidikan">Dinas Pendidikan</option>
                            <option value="Pusat Perpustakaan">Pusat Perpustakaan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        @error('sumber_perolehan') <span class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    {{-- Lokasi Rak --}}
                    <div>
                        <label for="lokasi_rak" class="block text-sm font-bold text-slate-700">Lokasi Rak</label>
                        <input type="text" id="lokasi_rak" wire:model="lokasi_rak" placeholder="Contoh: Lantai 2 - Rak A-12"
                               class="mt-2 h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none">
                        @error('lokasi_rak') <span class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</span> @enderror
                    </div>

                    {{-- Tanggal Penerimaan --}}
                    <div>
                        <label for="tanggal_penerimaan" class="block text-sm font-bold text-slate-700">Tanggal Penerimaan <span class="text-rose-500">*</span></label>
                        <input type="date" id="tanggal_penerimaan" wire:model="tanggal_penerimaan"
                               class="mt-2 h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none">
                        @error('tanggal_penerimaan') <span class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Catatan Tambahan (Opsional) --}}
                <div>
                    <label for="catatan" class="block text-sm font-bold text-slate-700">Catatan Tambahan (Opsional)</label>
                    <textarea id="catatan" wire:model="catatan" placeholder="Contoh: Kondisi buku baru, sampul plastik terpasang..." rows="4"
                              class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none resize-none"></textarea>
                    @error('catatan') <span class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                    <a href="{{ route('petugas.buku.show', $book->id_buku) }}"
                       class="flex h-12 items-center justify-center px-6 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" wire:loading.attr="disabled"
                            class="flex h-12 items-center justify-center gap-2 px-6 rounded-xl bg-[#142b3d] font-bold text-white transition hover:bg-[#1a3a52] disabled:opacity-50">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        Update Stok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
