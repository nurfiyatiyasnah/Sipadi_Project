@extends('layouts.petugas')
@section('title', 'Tambah Anggota Kepegawaian')

@section('content')
<div class="mx-auto max-w-[1180px] space-y-8">

    {{-- Header --}}
    <section class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('petugas.kepegawaian.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[#071426]">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Struktur Kepegawaian
            </a>
            <h2 class="mt-2 font-serif text-3xl font-bold text-[#071426]">Tambah Anggota Baru</h2>
            <p class="mt-1 text-slate-500">Masukkan data anggota struktur kepegawaian.</p>
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

    <form method="POST" action="{{ route('petugas.kepegawaian.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid gap-8 xl:grid-cols-[1fr_380px]">

            {{-- Left: Main Content --}}
            <div class="space-y-6">
                {{-- Nama --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <label for="nama" class="mb-3 block text-sm font-bold uppercase tracking-widest text-slate-600">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input id="nama" type="text" name="nama" value="{{ old('nama') }}"
                           placeholder="Masukkan nama lengkap..."
                           class="h-14 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 text-lg font-semibold focus:border-[#ffdc7c] focus:ring-[#ffdc7c] @error('nama') border-red-400 @enderror"
                           required>
                    @error('nama')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jabatan --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <label for="jabatan" class="mb-3 block text-sm font-bold uppercase tracking-widest text-slate-600">
                        Jabatan <span class="text-red-500">*</span>
                    </label>
                    <input id="jabatan" type="text" name="jabatan" value="{{ old('jabatan') }}"
                           placeholder="Masukkan jabatan..."
                           class="h-14 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 text-lg focus:border-[#ffdc7c] focus:ring-[#ffdc7c] @error('jabatan') border-red-400 @enderror"
                           required>
                    @error('jabatan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Urutan --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <label for="urutan" class="mb-3 block text-sm font-bold uppercase tracking-widest text-slate-600">
                        Urutan Tampil
                    </label>
                    <input id="urutan" type="number" name="urutan" value="{{ old('urutan', 0) }}"
                           placeholder="0"
                           class="h-14 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 text-lg focus:border-[#ffdc7c] focus:ring-[#ffdc7c] @error('urutan') border-red-400 @enderror">
                    <p class="mt-2 text-xs text-slate-500">Digunakan untuk mengurutkan tampilan (angka lebih kecil tampil lebih dulu).</p>
                    @error('urutan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Right: Sidebar --}}
            <div class="space-y-6">
                {{-- Foto Upload --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm" x-data="imageUpload()">
                    <p class="mb-3 text-sm font-bold uppercase tracking-widest text-slate-600">
                        Foto Anggota
                    </p>
                    <div class="relative flex min-h-[200px] cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-6 transition hover:border-[#ffdc7c] hover:bg-[#ffdc7c]/5"
                         @click="$refs.fileInput.click()"
                         @dragover.prevent="dragOver = true"
                         @dragleave.prevent="dragOver = false"
                         @drop.prevent="handleDrop($event)"
                         :class="{ 'border-[#ffdc7c] bg-[#ffdc7c]/5': dragOver }">

                        <template x-if="!preview">
                            <div class="text-center">
                                <i class="fa-solid fa-cloud-arrow-up text-4xl text-slate-400"></i>
                                <p class="mt-3 font-semibold text-slate-600">Klik atau seret gambar ke sini</p>
                                <p class="mt-1 text-xs text-slate-400">JPG, PNG, WebP - Maks. 2MB</p>
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

                        <input x-ref="fileInput" type="file" name="foto" accept="image/jpeg,image/png,image/webp"
                               class="hidden" @change="handleFileSelect($event)">
                    </div>
                    @error('foto')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <p class="mb-4 text-sm font-bold uppercase tracking-widest text-slate-600">Aksi</p>
                    <div class="space-y-3">
                        <button type="submit"
                                class="flex h-12 w-full items-center justify-center gap-3 rounded-xl bg-emerald-600 font-bold text-white transition hover:bg-emerald-700">
                            <i class="fa-solid fa-save"></i>
                            Simpan Anggota
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('imageUpload', () => ({
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
            if (file) {
                this.$refs.fileInput.files = event.dataTransfer.files;
                this.preview = URL.createObjectURL(file);
            }
        },
        removeImage() {
            this.preview = null;
            this.$refs.fileInput.value = '';
        }
    }))
})
</script>
@endsection
