@extends('layouts.petugas')
 
@section('title', 'Tambah Agenda Baru')
 
@section('content')
<div class="mx-auto max-w-[1000px] space-y-6">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-800 transition">
            <i class="fa-solid fa-house text-xs"></i>
        </a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <a href="{{ route('petugas.agenda.index') }}" class="hover:text-slate-800 transition">Agenda</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="font-medium text-slate-800">Tambah Baru</span>
    </nav>
 
    {{-- Header --}}
    <div>
        <h2 class="font-serif text-3xl font-bold leading-tight text-slate-900">Tambah Agenda Baru</h2>
        <p class="text-sm text-slate-500 mt-1">Lengkapi form di bawah ini untuk menerbitkan agenda perpustakaan.</p>
    </div>
 
    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-2xl">
            <div class="flex items-center gap-2 mb-2">
                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                <p class="text-red-800 text-sm font-semibold">Terdapat kesalahan:</p>
            </div>
            <ul class="list-disc list-inside text-xs text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
 
    {{-- Form --}}
    <form action="{{ route('petugas.agenda.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm space-y-6">
        @csrf
        <input type="hidden" name="status_event" id="status_event" value="terbit">
 
        {{-- Judul Agenda --}}
        <div class="space-y-2">
            <label for="judul_event" class="block text-sm font-semibold text-slate-800">
                Judul Agenda <span class="text-red-500">*</span>
            </label>
            <input type="text" name="judul_event" id="judul_event" value="{{ old('judul_event') }}"
                   placeholder="Contoh: Seminar Literasi Digital 2024"
                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 px-5 text-sm outline-none transition focus:border-slate-300 focus:bg-white placeholder:text-slate-400">
        </div>
 
        {{-- Grid Tanggal, Waktu & Lokasi --}}
        <div class="grid gap-6 md:grid-cols-2">
            {{-- Tanggal & Waktu --}}
            <div class="space-y-2">
                <label for="tanggal_waktu" class="block text-sm font-semibold text-slate-800">
                    Tanggal & Waktu <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="tanggal_waktu" id="tanggal_waktu" value="{{ old('tanggal_waktu') }}"
                       class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 px-5 text-sm outline-none transition focus:border-slate-300 focus:bg-white">
            </div>
 
            {{-- Lokasi --}}
            <div class="space-y-2">
                <label for="lokasi" class="block text-sm font-semibold text-slate-800">
                    Lokasi <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-5 flex items-center text-slate-400">
                        <i class="fa-solid fa-location-dot"></i>
                    </span>
                    <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}"
                           placeholder="Nama Ruangan / Link Google Maps"
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-12 pr-5 text-sm outline-none transition focus:border-slate-300 focus:bg-white placeholder:text-slate-400">
                </div>
            </div>
        </div>
 
        {{-- Kategori Agenda --}}
        <div class="space-y-2">
            <label for="kategori" class="block text-sm font-semibold text-slate-800">
                Kategori Agenda
            </label>
            <input type="text" name="kategori" id="kategori" value="{{ old('kategori') }}"
                   placeholder="Contoh: Seminar Nasional, Diskusi Internal, Workshop Mahasiswa"
                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 px-5 text-sm outline-none transition focus:border-slate-300 focus:bg-white placeholder:text-slate-400">
        </div>
 
        {{-- Deskripsi Lengkap --}}
        <div class="space-y-2">
            <label for="deskripsi" class="block text-sm font-semibold text-slate-800">
                Deskripsi Lengkap <span class="text-red-500">*</span>
            </label>
            <textarea name="deskripsi" id="deskripsi" rows="5"
                      placeholder="Tuliskan rincian kegiatan, narasumber, dan informasi penting lainnya..."
                      class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 px-5 text-sm outline-none transition focus:border-slate-300 focus:bg-white placeholder:text-slate-400 resize-none">{{ old('deskripsi') }}</textarea>
        </div>
 
        {{-- Gambar Banner Agenda --}}
        <div class="space-y-2">
            <label class="block text-sm font-semibold text-slate-800">
                Gambar Banner Agenda
            </label>
            <div id="upload-area"
                 class="relative border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center cursor-pointer hover:border-slate-300 hover:bg-slate-50 transition duration-200"
                 onclick="document.getElementById('gambar').click()">
                <input type="file" name="gambar" id="gambar" accept="image/*" class="hidden" onchange="previewImage(event)">
                
                {{-- Placeholder --}}
                <div id="upload-placeholder" class="space-y-3">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-500 text-xl">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Unggah file atau seret dan lepas</p>
                        <p class="text-xs text-slate-400 mt-1">PNG, JPG, GIF hingga 5MB</p>
                    </div>
                </div>
 
                {{-- Preview --}}
                <div id="upload-preview" class="hidden space-y-3">
                    <img id="preview-img" src="" alt="Preview" class="max-h-48 mx-auto rounded-xl object-cover shadow-sm">
                    <p id="preview-name" class="text-xs text-slate-500"></p>
                    <button type="button" onclick="event.stopPropagation(); clearImage()" class="text-xs text-red-500 hover:text-red-700 font-semibold transition">
                        <i class="fa-solid fa-trash mr-1.5"></i> Hapus Gambar
                    </button>
                </div>
            </div>
        </div>
 
        {{-- Tampilkan di Beranda --}}
        <div class="flex items-center justify-between py-4 border-t border-slate-100">
            <div>
                <h4 class="text-sm font-semibold text-slate-800">Tampilkan di Beranda Utama</h4>
                <p class="text-xs text-slate-400 mt-1">Tampilkan agenda ini pada beranda depan portal SIPADI.</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="tampilkan_beranda" value="1" {{ old('tampilkan_beranda') ? 'checked' : '' }} class="sr-only peer">
                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0e1f30]"></div>
            </label>
        </div>
 
        {{-- Status Publikasi --}}
        <div class="flex items-center justify-between py-4 border-t border-slate-100">
            <div>
                <h4 class="text-sm font-semibold text-slate-800">Status Publikasi</h4>
                <p class="text-xs text-slate-400 mt-1">Terbitkan agenda secara langsung setelah disimpan.</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="status_toggle" checked class="sr-only peer">
                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0e1f30]"></div>
            </label>
        </div>
 
        {{-- Action Buttons --}}
        <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
            <a href="{{ route('petugas.agenda.index') }}"
               class="flex h-12 items-center justify-center rounded-xl border border-slate-200 px-6 font-bold text-slate-600 hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit"
                    class="flex h-12 items-center justify-center gap-2 rounded-xl bg-[#0e1f30] px-6 font-bold text-white hover:bg-[#1a2f44] transition">
                <i class="fa-solid fa-save"></i>
                Simpan Agenda
            </button>
        </div>
    </form>
</div>
 
<script>
    // Toggle Status Event
    document.getElementById('status_toggle').addEventListener('change', function() {
        document.getElementById('status_event').value = this.checked ? 'terbit' : 'draft';
    });
 
    // Image Preview
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;
 
        const placeholder = document.getElementById('upload-placeholder');
        const preview = document.getElementById('upload-preview');
        const previewImg = document.getElementById('preview-img');
        const previewName = document.getElementById('preview-name');
 
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewName.textContent = file.name;
            placeholder.classList.add('hidden');
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
 
    function clearImage() {
        const input = document.getElementById('gambar');
        const placeholder = document.getElementById('upload-placeholder');
        const preview = document.getElementById('upload-preview');
 
        input.value = '';
        placeholder.classList.remove('hidden');
        preview.classList.add('hidden');
    }
 
    // Drag & Drop
    const uploadArea = document.getElementById('upload-area');
 
    ['dragenter', 'dragover'].forEach(event => {
        uploadArea.addEventListener(event, function(e) {
            e.preventDefault();
            uploadArea.classList.add('border-slate-400', 'bg-slate-50');
        });
    });
 
    ['dragleave', 'drop'].forEach(event => {
        uploadArea.addEventListener(event, function(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-slate-400', 'bg-slate-50');
        });
    });
 
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const input = document.getElementById('gambar');
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            previewImage({ target: { files: [file] } });
        }
    });
</script>
@endsection
