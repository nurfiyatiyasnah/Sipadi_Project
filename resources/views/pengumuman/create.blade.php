@extends('layouts.petugas')
@section('title', 'Tambah Pengumuman')

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
            <h2 class="mt-2 font-serif text-3xl font-bold text-[#0e1f30]">Tambah Pengumuman</h2>
            <p class="mt-1 text-slate-500">Buat pengumuman baru untuk ditampilkan di portal sistem.</p>
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

    <form method="POST" action="{{ route('petugas.pengumuman.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="target_pengguna" value="Semua">
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
                        <input id="judul" type="text" name="judul" value="{{ old('judul') }}"
                               placeholder="Masukkan judul pengumuman"
                               class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-slate-300 focus:outline-none focus:ring-0 @error('judul') border-red-400 @enderror"
                               required>
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
                                      required>{{ old('isi') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Konfigurasi Tayang --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm space-y-5">
                    <h3 class="text-md font-bold text-[#0e1f30] border-b border-slate-100 pb-3">Konfigurasi Tayang</h3>

                    <div class="space-y-5">
                        {{-- Rentang Tanggal --}}
                        <div>
                            <label class="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">
                                Rentang Tanggal Aktif <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-3">
                                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', now()->toDateString()) }}"
                                       class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-slate-300 focus:outline-none focus:ring-0"
                                       required>
                                <span class="text-xs font-bold text-slate-400">s/d</span>
                                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', now()->addDays(7)->toDateString()) }}"
                                       class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-slate-300 focus:outline-none focus:ring-0"
                                       required>
                            </div>
                        </div>

                        {{-- Status Awal --}}
                        <div>
                            <label for="status_pengumuman" class="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">
                                Status Awal <span class="text-red-500">*</span>
                            </label>
                            <select id="status_pengumuman" name="status_pengumuman"
                                    class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-slate-300 focus:outline-none focus:ring-0"
                                    required>
                                <option value="terbit" @selected(old('status_pengumuman') === 'terbit')>Aktif (Langsung Tayang)</option>
                                <option value="draf" @selected(old('status_pengumuman') === 'draf')>Draf</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Dokumen Lampiran --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm space-y-5">
                    <h3 class="text-md font-bold text-[#0e1f30] border-b border-slate-100 pb-3">Dokumen Lampiran</h3>
                    <div>
                        <label for="file_lampiran" class="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">
                            Pilih File Lampiran (Bisa lebih dari satu)
                        </label>
                        <input id="file_lampiran" type="file" name="file_lampiran[]" multiple
                               class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#0e1f30] file:text-white hover:file:bg-[#1a344f] transition cursor-pointer">
                        <p class="mt-2 text-xs text-slate-400">Mendukung format file dokumen & gambar. Maks. 10MB per file.</p>
                    </div>
                </div>
            </div>

            {{-- Right Side: Settings & Images --}}
            <div class="space-y-6">
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
                <div class="rounded-3xl bg-white p-6 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 border-b border-slate-100 pb-3">Target & Visibilitas</h3>

                    {{-- Prioritas --}}
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-500">Prioritas</label>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 text-sm font-semibold text-slate-600 cursor-pointer">
                                <input type="radio" name="prioritas" value="Normal" checked
                                       class="text-[#0e1f30] focus:ring-0">
                                Normal
                            </label>
                            <label class="flex items-center gap-2 text-sm font-semibold text-slate-600 cursor-pointer">
                                <input type="radio" name="prioritas" value="Penting"
                                       class="text-[#0e1f30] focus:ring-0">
                                Penting (Highlight)
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <button type="submit"
                            class="flex h-12 w-full items-center justify-center gap-3 rounded-xl bg-[#0e1f30] hover:bg-[#1a344f] font-bold text-white transition">
                        <i class="fa-regular fa-floppy-disk"></i>
                        Simpan Pengumuman
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function imageUpload() {
    return {
        preview: null,
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
