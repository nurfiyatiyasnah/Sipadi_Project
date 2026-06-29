<div class="mx-auto max-w-[1180px] space-y-6">
    {{-- Breadcrumb --}}
    <nav class="flex text-sm text-slate-500 gap-2">
        <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-800 transition">Dashboard</a>
        <span>&rsaquo;</span>
        <a href="{{ route('petugas.koleksi') }}" class="hover:text-slate-800 transition">Buku</a>
        <span>&rsaquo;</span>
        <span class="text-slate-800 font-semibold">Tambah Buku Baru</span>
    </nav>

    {{-- Header --}}
    <div>
        <h2 class="font-serif text-3xl font-bold text-[#071426]">Tambah Buku Baru</h2>
        <p class="text-sm text-slate-500 mt-1">Daftarkan koleksi buku baru ke dalam sistem perpustakaan.</p>
    </div>

    {{-- Form Form --}}
    <form wire:submit.prevent="save" class="grid gap-6 lg:grid-cols-3">
        {{-- Left Side: Main Info (2 Columns wide) --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Informasi Utama Card --}}
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 space-y-4">
                <h3 class="text-lg font-bold text-[#071426] border-b border-slate-100 pb-3">
                    <i class="fa-solid fa-circle-info text-[#ffdc7c] mr-2"></i>
                    Informasi Utama
                </h3>

                {{-- Judul Buku --}}
                <div>
                    <label for="judul" class="block text-sm font-bold text-slate-700">Judul Buku <span class="text-rose-500">*</span></label>
                    <input type="text" id="judul" wire:model="judul" placeholder="Masukkan judul buku lengkap"
                           class="mt-2 h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none">
                    @error('judul') <span class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Penulis & Penerbit --}}
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="penulis" class="block text-sm font-bold text-slate-700">Penulis <span class="text-rose-500">*</span></label>
                        <input type="text" id="penulis" wire:model="penulis" placeholder="Nama penulis"
                               class="mt-2 h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none">
                        @error('penulis') <span class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="penerbit" class="block text-sm font-bold text-slate-700">Penerbit <span class="text-rose-500">*</span></label>
                        <input type="text" id="penerbit" wire:model="penerbit" placeholder="Nama penerbit"
                               class="mt-2 h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none">
                        @error('penerbit') <span class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Tahun Terbit & ISBN --}}
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="tahun_terbit" class="block text-sm font-bold text-slate-700">Tahun Terbit <span class="text-rose-500">*</span></label>
                        <input type="number" id="tahun_terbit" wire:model="tahun_terbit" placeholder="YYYY"
                               class="mt-2 h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none">
                        @error('tahun_terbit') <span class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="isbn" class="block text-sm font-bold text-slate-700">ISBN <span class="text-rose-500">*</span></label>
                        <input type="text" id="isbn" wire:model="isbn" placeholder="Contoh: 978-602-8519-93-9"
                               class="mt-2 h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none">
                        @error('isbn') <span class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- Deskripsi Card --}}
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 space-y-4">
                <h3 class="text-lg font-bold text-[#071426] border-b border-slate-100 pb-3">
                    <i class="fa-solid fa-align-left text-[#ffdc7c] mr-2"></i>
                    Deskripsi / Sinopsis
                </h3>
                <div>
                    <textarea id="deskripsi" wire:model="deskripsi" placeholder="Tuliskan sinopsis atau deskripsi singkat mengenai buku ini..." rows="5"
                              class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none resize-none"></textarea>
                    @error('deskripsi') <span class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Right Side: Category, Stock, Cover --}}
        <div class="space-y-6">
            {{-- Kategori & Stok Card --}}
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 space-y-4">
                <h3 class="text-lg font-bold text-[#071426] border-b border-slate-100 pb-3">
                    <i class="fa-solid fa-tags text-[#ffdc7c] mr-2"></i>
                    Kategori & Stok
                </h3>

                {{-- Kategori --}}
                <div>
                    <label for="id_kategori" class="block text-sm font-bold text-slate-700">Kategori <span class="text-rose-500">*</span></label>
                    <select id="id_kategori" wire:model="id_kategori"
                            class="mt-2 h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none">
                        <option value="">Pilih kategori...</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id_kategori }}">{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                    @error('id_kategori') <span class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Stok Awal --}}
                <div>
                    <label for="stok_awal" class="block text-sm font-bold text-slate-700">Stok Awal <span class="text-rose-500">*</span></label>
                    <div class="relative mt-2">
                        <input type="number" id="stok_awal" wire:model="stok_awal" min="0" max="100"
                               class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 pr-24 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] outline-none">
                        <span class="absolute inset-y-0 right-4 flex items-center text-xs font-semibold text-slate-400">
                            Eksemplar
                        </span>
                    </div>
                    @error('stok_awal') <span class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Upload Cover Card --}}
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 space-y-4">
                <h3 class="text-lg font-bold text-[#071426] border-b border-slate-100 pb-3">
                    <i class="fa-solid fa-image text-[#ffdc7c] mr-2"></i>
                    Upload Cover
                </h3>

                <div class="space-y-4">
                    {{-- Cover Drag & Drop Area --}}
                    <div class="relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/50 p-6 text-center hover:bg-slate-50 transition cursor-pointer">
                        <input type="file" id="cover_file" wire:model="cover_file" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                        
                        @if ($cover_file)
                            <div class="h-48 w-36 overflow-hidden rounded-xl bg-slate-100 border border-slate-200 shadow-sm relative">
                                <img src="{{ $cover_file->temporaryUrl() }}" class="h-full w-full object-cover">
                            </div>
                            <p class="mt-2 text-xs font-semibold text-slate-600 truncate max-w-full">
                                {{ $cover_file->getClientOriginalName() }}
                            </p>
                        @else
                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-[#ffdc7c]/10 text-[#071426] text-xl">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </span>
                            <p class="mt-3 text-sm font-bold text-slate-700">Klik untuk upload atau drag and drop</p>
                            <p class="mt-1 text-xs text-slate-400">PNG, JPG, JPEG (Max. 2MB)</p>
                        @endif
                    </div>
                    
                    @error('cover_file') <span class="mt-1 block text-xs text-rose-600 font-medium">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Form Actions (Full width) --}}
        <div class="lg:col-span-3 flex items-center justify-end gap-3 border-t border-slate-200/60 pt-6">
            <a href="{{ route('petugas.koleksi') }}"
               class="flex h-12 items-center justify-center px-6 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit" wire:loading.attr="disabled"
                    class="flex h-12 items-center justify-center gap-2 px-6 rounded-xl bg-[#142b3d] font-bold text-white transition hover:bg-[#1a3a52] disabled:opacity-50">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Buku
            </button>
        </div>
    </form>
</div>
