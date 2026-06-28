@extends('layouts.petugas')
@section('title', 'Edit Berita')

@section('content')
<div class="mx-auto max-w-[1180px] space-y-8">
    <section class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('petugas.berita.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[#071426]">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Daftar Berita
            </a>
            <h2 class="mt-2 font-serif text-3xl font-bold text-[#071426]">Edit Berita</h2>
            <p class="mt-1 text-slate-500">Perbarui konten, kategori, thumbnail, atau status publikasi berita.</p>
        </div>
    </section>

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

    <form method="POST" action="{{ route('petugas.berita.update', $berita) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid gap-8 xl:grid-cols-[1fr_380px]">
            <div class="space-y-6">
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <label for="judul" class="mb-3 block text-sm font-bold uppercase tracking-widest text-slate-600">
                        Judul Berita <span class="text-red-500">*</span>
                    </label>
                    <input id="judul" type="text" name="judul" value="{{ old('judul', $berita->judul) }}"
                           placeholder="Masukkan judul berita..."
                           class="h-14 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 text-lg font-semibold focus:border-[#ffdc7c] focus:ring-[#ffdc7c] @error('judul') border-red-400 @enderror"
                           required>
                    @error('judul')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <label for="isi" class="mb-3 block text-sm font-bold uppercase tracking-widest text-slate-600">
                        Isi Berita
                    </label>
                    <textarea id="isi" name="isi" rows="14"
                              placeholder="Tulis isi berita di sini..."
                              class="w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm leading-relaxed focus:border-[#ffdc7c] focus:ring-[#ffdc7c] @error('isi') border-red-400 @enderror">{{ old('isi', $berita->isi) }}</textarea>
                    @error('isi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl bg-white p-6 shadow-sm" x-data="imageUpload(@js($berita->gambar ? Storage::url($berita->gambar) : null))">
                    <p class="mb-3 text-sm font-bold uppercase tracking-widest text-slate-600">
                        Thumbnail Berita
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
                                <p class="mt-1 text-xs text-slate-400">JPG, PNG, WebP - Maks. 5MB</p>
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
                    @error('gambar')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <label for="id_kategori_berita" class="mb-3 block text-sm font-bold uppercase tracking-widest text-slate-600">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select id="id_kategori_berita" name="id_kategori_berita"
                            class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm focus:border-[#ffdc7c] focus:ring-[#ffdc7c] @error('id_kategori_berita') border-red-400 @enderror"
                            required>
                        <option value="">Pilih kategori...</option>
                        @foreach ($kategoriList as $kat)
                            <option value="{{ $kat->id_kategori_berita }}" @selected(old('id_kategori_berita', $berita->id_kategori_berita) == $kat->id_kategori_berita)>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kategori_berita')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-center justify-between gap-4">
                        <p class="text-sm font-bold uppercase tracking-widest text-slate-600">Publikasi</p>
                        <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider {{ $berita->status_berita === \App\Models\Berita::STATUS_PUBLISHED ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $berita->status_berita === \App\Models\Berita::STATUS_PUBLISHED ? 'Terbit' : 'Draft' }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        <button type="submit" name="status_berita" value="{{ \App\Models\Berita::STATUS_PUBLISHED }}"
                                class="flex h-12 w-full items-center justify-center gap-3 rounded-xl bg-emerald-600 font-bold text-white transition hover:bg-emerald-700">
                            <i class="fa-solid fa-paper-plane"></i>
                            Simpan & Terbitkan
                        </button>
                        <button type="submit" name="status_berita" value="{{ \App\Models\Berita::STATUS_DRAFT }}"
                                class="flex h-12 w-full items-center justify-center gap-3 rounded-xl border-2 border-slate-200 font-bold text-slate-600 transition hover:border-[#ffdc7c] hover:bg-[#ffdc7c]/10">
                            <i class="fa-regular fa-floppy-disk"></i>
                            Simpan sebagai Draft
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function imageUpload(initialPreview = null) {
    return {
        preview: initialPreview,
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
    };
}
</script>
@endsection
