@extends('layouts.petugas')
@section('title', 'Edit Pengumuman')

@section('content')
<div class="mx-auto max-w-[1180px] space-y-6">

    {{-- Header --}}
    <section class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('petugas.pengumuman.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[#0e1f30]">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Daftar Pengumuman
            </a>
            <h2 class="mt-2 font-serif text-3xl font-bold text-[#0e1f30]">Edit Konten Pengumuman</h2>
            <p class="mt-1 text-slate-500 font-medium">Perbarui informasi dan konfigurasi tayang pengumuman.</p>
        </div>
        <div>
            <a href="{{ route('petugas.pengumuman.index') }}"
               class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-500 hover:bg-slate-50 transition">
                Batal
            </a>
        </div>
    </section>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-6 py-4">
            <div class="flex items-center gap-3 text-red-800">
                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                <p class="font-bold">Terdapat kesalahan pada input Anda:</p>
            </div>
            <ul class="mt-3 list-inside list-disc space-y-1 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('petugas.pengumuman.update', $pengumuman) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid gap-6 xl:grid-cols-[1fr_380px]">

            {{-- Left Side: Main Content --}}
            <div class="space-y-6">
                {{-- Informasi Utama --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm space-y-5">
                    <h3 class="text-md font-bold text-[#0e1f30] border-b border-slate-100 pb-3">Informasi Utama</h3>
                    
                    {{-- Judul --}}
                    <div>
                        <label for="judul" class="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">
                            Judul Pengumuman <span class="text-red-500">*</span>
                        </label>
                       <input
                            id="judul"
                            type="text"
                            name="judul"
                            value="{{ old('judul', $pengumuman->judul) }}"
                            placeholder="Masukkan judul pengumuman"
                            @class([
                                'h-12 w-full rounded-xl border bg-slate-50 px-4 text-sm focus:border-slate-300 focus:outline-none focus:ring-0',
                                'border-slate-200' => !$errors->has('judul'),
                                'border-red-400' => $errors->has('judul'),
                            ])
                            required
                        >
                        @error('judul')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Isi --}}
                    <div>
                        <label for="isi" class="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">
                            Isi Pengumuman <span class="text-red-500">*</span>
                        </label>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 overflow-hidden">
                            {{-- Editor toolbar style --}}
                            <div class="flex items-center gap-3 border-b border-slate-200 bg-slate-100/80 px-4 py-2 text-slate-500 text-xs font-semibold">
                                <button type="button" class="hover:text-slate-800"><i class="fa-solid fa-bold"></i></button>
                                <button type="button" class="hover:text-slate-800"><i class="fa-solid fa-italic"></i></button>
                                <button type="button" class="hover:text-slate-800"><i class="fa-solid fa-underline"></i></button>
                                <span class="h-4 w-px bg-slate-300"></span>
                                <button type="button" class="hover:text-slate-800"><i class="fa-solid fa-list-ul"></i></button>
                                <button type="button" class="hover:text-slate-800"><i class="fa-solid fa-list-ol"></i></button>
                                <span class="h-4 w-px bg-slate-300"></span>
                                <button type="button" class="hover:text-slate-800"><i class="fa-solid fa-link"></i></button>
                            </div>
                            <textarea id="isi" name="isi" rows="12"
                                      placeholder="Tuliskan isi pengumuman..."
                                      class="w-full border-0 bg-transparent px-4 py-3 text-sm focus:outline-none focus:ring-0"
                                      required>{{ old('isi', $pengumuman->isi) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Dokumen Lampiran --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm space-y-5">
                    <h3 class="text-md font-bold text-[#0e1f30] border-b border-slate-100 pb-3">Dokumen Lampiran</h3>
                    
                    @if ($pengumuman->file_lampiran && count($pengumuman->file_lampiran) > 0)
                        <div class="space-y-3">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Lampiran Saat Ini:</p>
                            <div class="grid gap-3 sm:grid-cols-2">
                                @foreach ($pengumuman->file_lampiran as $item)
                                    <div class="flex items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-400 border border-slate-100 text-lg flex-shrink-0">
                                            @if(Str::endsWith($item['name'], ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                                <i class="fa-regular fa-image text-emerald-500"></i>
                                            @else
                                                <i class="fa-regular fa-file-pdf text-red-500"></i>
                                            @endif
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-bold text-slate-700 truncate" title="{{ $item['name'] }}">{{ $item['name'] }}</p>
                                            <p class="text-[10px] font-semibold text-slate-400 mt-0.5">{{ $item['size'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="file_lampiran" class="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">
                            Upload File Lampiran Baru (Akan menggantikan lampiran lama)
                        </label>
                        <input id="file_lampiran" type="file" name="file_lampiran[]" multiple
                               class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#0e1f30] file:text-white hover:file:bg-[#1a344f] transition cursor-pointer">
                        <p class="mt-2 text-xs text-slate-400">Mendukung format file dokumen & gambar. Maks. 10MB per file.</p>
                    </div>
                </div>
            </div>

            {{-- Right Side: Settings & Images --}}
            <div class="space-y-6">
                {{-- Penjadwalan --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm space-y-5">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 border-b border-slate-100 pb-3">Penjadwalan</h3>
                    
                    {{-- Status Publikasi --}}
                    <div>
                        <label for="status_pengumuman" class="mb-2 block text-xs font-semibold text-slate-500">Status Publikasi</label>
                        <select id="status_pengumuman" name="status_pengumuman"
                                class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm focus:border-slate-300 focus:outline-none focus:ring-0">
                            <option value="terbit" @selected(old('status_pengumuman', $pengumuman->status_pengumuman) === 'terbit')>Sedang Aktif (Published)</option>
                            <option value="draf" @selected(old('status_pengumuman', $pengumuman->status_pengumuman) === 'draf')>Draf</option>
                        </select>
                    </div>

                    {{-- Tanggal Mulai --}}
                    <div>
                        <label for="tanggal_mulai" class="mb-2 block text-xs font-semibold text-slate-500">Tanggal Mulai</label>
                        <input id="tanggal_mulai" type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $pengumuman->tanggal_mulai?->toDateString()) }}"
                               class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm focus:border-slate-300 focus:outline-none focus:ring-0"
                               required>
                    </div>

                    {{-- Tanggal Selesai --}}
                    <div>
                        <label for="tanggal_selesai" class="mb-2 block text-xs font-semibold text-slate-500">Tanggal Berakhir</label>
                        <input id="tanggal_selesai" type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $pengumuman->tanggal_selesai?->toDateString()) }}"
                               class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm focus:border-slate-300 focus:outline-none focus:ring-0"
                               required>
                    </div>
                </div>

                {{-- Gambar Utama --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm" x-data="imageUpload()">
                    <p class="mb-3 text-xs font-bold uppercase tracking-widest text-slate-400">
                        Gambar Utama
                    </p>
                    <div class="relative flex min-h-[200px] cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 p-6 transition hover:border-slate-300 hover:bg-slate-50/50"
                         @click="$refs.fileInput.click()"
                         @dragover.prevent="dragOver = true"
                         @dragleave.prevent="dragOver = false"
                         @drop.prevent="handleDrop($event)"
                         :class="{ 'border-slate-300 bg-slate-100': dragOver }">

                        <template x-if="!preview">
                            <div class="text-center">
                                <i class="fa-solid fa-cloud-arrow-up text-4xl text-slate-400"></i>
                                <p class="mt-3 text-xs font-bold text-slate-500">Klik atau seret gambar ke sini</p>
                                <p class="mt-1 text-[10px] text-slate-400">JPG, PNG, WebP - Maks. 5MB</p>
                            </div>
                        </template>

                        <template x-if="preview">
                            <div class="relative w-full">
                                <img :src="preview" alt="Preview" class="w-full rounded-xl object-cover">
                                <button type="button" @click.stop="removeImage()"
                                        class="absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-white shadow-lg transition hover:bg-red-600">
                                    <i class="fa-solid fa-xmark text-sm"></i>
                                </button>
                            </div>
                        </template>

                        <input x-ref="fileInput" type="file" name="gambar" accept="image/jpeg,image/png,image/webp"
                               class="hidden" @change="handleFileSelect($event)">
                    </div>
                </div>

                {{-- Target & Visibilitas --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm space-y-5">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 border-b border-slate-100 pb-3">Target & Visibilitas</h3>

                    {{-- Target Pengguna --}}
                    <div>
                        <label for="target_pengguna" class="mb-2 block text-xs font-semibold text-slate-500">Target Pengguna</label>
                        <select id="target_pengguna" name="target_pengguna"
                                class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm focus:border-slate-300 focus:outline-none focus:ring-0">
                            <option value="Semua" @selected(old('target_pengguna', $pengumuman->target_pengguna) === 'Semua')>Semua</option>
                            <option value="Siswa / Mahasiswa" @selected(old('target_pengguna', $pengumuman->target_pengguna) === 'Siswa / Mahasiswa')>Siswa / Mahasiswa</option>
                            <option value="Dosen" @selected(old('target_pengguna', $pengumuman->target_pengguna) === 'Dosen')>Dosen</option>
                            <option value="Petugas" @selected(old('target_pengguna', $pengumuman->target_pengguna) === 'Petugas')>Petugas</option>
                        </select>
                    </div>

                    {{-- Prioritas --}}
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-500">Prioritas</label>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 text-sm font-semibold text-slate-600 cursor-pointer">
                                <input type="radio" name="prioritas" value="Normal" @checked(old('prioritas', $pengumuman->prioritas) === 'Normal')
                                       class="text-[#0e1f30] focus:ring-0">
                                Normal
                            </label>
                            <label class="flex items-center gap-2 text-sm font-semibold text-slate-600 cursor-pointer">
                                <input type="radio" name="prioritas" value="Penting" @checked(old('prioritas', $pengumuman->prioritas) === 'Penting')
                                       class="text-[#0e1f30] focus:ring-0">
                                Penting (Highlight)
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Informasi Tambahan --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm space-y-4 text-xs font-semibold text-slate-500">
                    <h3 class="font-bold uppercase tracking-widest text-slate-400 border-b border-slate-100 pb-3">Informasi Tambahan</h3>
                    <div class="flex justify-between">
                        <span>Dibuat oleh:</span>
                        <span class="text-slate-700 font-bold">{{ $pengumuman->petugas?->nama_petugas ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Terakhir diubah:</span>
                        <span class="text-slate-700 font-bold">{{ $pengumuman->updated_at ? $pengumuman->updated_at->locale('id')->diffForHumans() : '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Dilihat:</span>
                        <span class="text-slate-700 font-bold">{{ number_format($pengumuman->total_views) }} kali</span>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <button type="submit"
                            class="flex h-12 w-full items-center justify-center gap-3 rounded-xl bg-[#0e1f30] hover:bg-[#1a344f] font-bold text-white transition">
                        <i class="fa-regular fa-floppy-disk"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function imageUpload() {
    return {
        preview: @js($pengumuman->gambar ? Storage::url($pengumuman->gambar) : null),
        dragOver: false,
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.preview = URL.createObjectURL(file);
            }
        },
        handleDrop(event) {
            this.dragOver = false;
            const file = event.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                this.$refs.fileInput.files = event.dataTransfer.files;
                this.preview = URL.createObjectURL(file);
            }
        },
        removeImage() {
            this.preview = null;
            this.$refs.fileInput.value = '';
        }
    }
}
</script>
@endsection
