@extends('layouts.petugas')
 
@section('title', 'Edit Agenda')
 
@section('content')
<div class="mx-auto max-w-[1180px] space-y-6">
    {{-- Breadcrumbs & Back Button --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-800 transition">
                <i class="fa-solid fa-house text-xs"></i>
            </a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <a href="{{ route('petugas.agenda.index') }}" class="hover:text-slate-800 transition">Agenda</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="font-medium text-slate-800">Edit Agenda</span>
        </nav>
        <div>
            <a href="{{ route('petugas.agenda.index') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>
    </div>
 
    {{-- Header --}}
    <div>
        <h2 class="font-serif text-3xl font-bold leading-tight text-slate-900">Edit Agenda Kegiatan</h2>
        <p class="text-sm text-slate-500 mt-1">Perbarui detail agenda untuk kegiatan perpustakaan mendatang.</p>
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
    <form action="{{ route('petugas.agenda.update', $agenda) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <input type="hidden" name="status_event" id="status_event" value="{{ old('status_event', $agenda->status_event ?? 'draft') }}">
 
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Left Side: main form --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm space-y-5">
                    
                    {{-- Judul Agenda --}}
                    <div class="space-y-2">
                        <label for="judul_event" class="block text-sm font-semibold text-slate-800">Judul Agenda</label>
                        <input type="text" name="judul_event" id="judul_event" value="{{ old('judul_event', $agenda->judul_event) }}"
                               placeholder="Contoh: Webinar Literasi Digital 2024"
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 px-5 text-sm outline-none transition focus:border-slate-300 focus:bg-white placeholder:text-slate-400">
                    </div>
 
                    {{-- Tanggal Pelaksanaan & Waktu --}}
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="tanggal_mulai" class="block text-sm font-semibold text-slate-800">Tanggal Pelaksanaan</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                                   value="{{ old('tanggal_mulai', $agenda->tanggal_mulai ? $agenda->tanggal_mulai->format('Y-m-d') : '') }}"
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 px-5 text-sm outline-none transition focus:border-slate-300 focus:bg-white">
                        </div>
                        <div class="space-y-2">
                            <label for="jam_mulai" class="block text-sm font-semibold text-slate-800">Waktu (Jam)</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" 
                                   value="{{ old('jam_mulai', $agenda->jam_mulai ? substr($agenda->jam_mulai, 0, 5) : '') }}"
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 px-5 text-sm outline-none transition focus:border-slate-300 focus:bg-white">
                        </div>
                    </div>
 
                    {{-- Lokasi / Tempat --}}
                    <div class="space-y-2">
                        <label for="lokasi" class="block text-sm font-semibold text-slate-800">Lokasi / Tempat</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-5 flex items-center text-slate-400">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>
                            <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $agenda->lokasi) }}"
                                   placeholder="Nama Ruangan / Link Google Maps"
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-12 pr-5 text-sm outline-none transition focus:border-slate-300 focus:bg-white placeholder:text-slate-400">
                        </div>
                    </div>
 
                    {{-- Kategori --}}
                    <div class="space-y-2">
                        <label for="kategori" class="block text-sm font-semibold text-slate-800">Kategori</label>
                        <input type="text" name="kategori" id="kategori" value="{{ old('kategori', $agenda->kategori) }}"
                               placeholder="Contoh: Seminar Nasional"
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 px-5 text-sm outline-none transition focus:border-slate-300 focus:bg-white placeholder:text-slate-400">
                    </div>
 
                    {{-- Deskripsi Agenda --}}
                    <div class="space-y-2">
                        <label for="deskripsi" class="block text-sm font-semibold text-slate-800">Deskripsi Agenda</label>
                        <textarea name="deskripsi" id="deskripsi" rows="5"
                                  placeholder="Tuliskan rincian kegiatan, narasumber, dan informasi penting lainnya..."
                                  class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 px-5 text-sm outline-none transition focus:border-slate-300 focus:bg-white placeholder:text-slate-400 resize-none">{{ old('deskripsi', $agenda->deskripsi) }}</textarea>
                    </div>
 
                    {{-- Action buttons --}}
                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                        <a href="{{ route('petugas.agenda.edit', $agenda) }}"
                           class="flex h-12 items-center justify-center rounded-xl border border-slate-200 px-6 font-bold text-slate-600 hover:bg-slate-50 transition">
                            Reset Perubahan
                        </a>
                        <button type="submit"
                                class="flex h-12 items-center justify-center gap-2 rounded-xl bg-[#0e1f30] px-6 font-bold text-white hover:bg-[#1a2f44] transition">
                            <i class="fa-solid fa-save"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
 
            {{-- Right Side: cards --}}
            <div class="space-y-6">
                
                {{-- Thumbnail Agenda Card --}}
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-800">Thumbnail Agenda</h3>
                    
                    {{-- Preview image --}}
                    <div id="upload-area"
                         class="relative border border-slate-200 rounded-2xl overflow-hidden bg-slate-50 text-center cursor-pointer p-4 group"
                         onclick="document.getElementById('gambar').click()">
                        <input type="file" name="gambar" id="gambar" accept="image/*" class="hidden" onchange="previewImage(event)">
                        
                        @if($agenda->gambar)
                            <img id="preview-img" src="{{ Storage::url($agenda->gambar) }}" alt="Thumbnail" class="max-h-48 mx-auto rounded-xl object-cover shadow-sm">
                            <div class="text-[10px] text-slate-400 mt-2">Klik untuk mengganti gambar</div>
                        @else
                            <div id="upload-placeholder" class="py-6 space-y-2">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-500 text-lg">
                                    <i class="fa-solid fa-image"></i>
                                </span>
                                <p class="text-xs font-semibold text-slate-600">Pilih File Poster</p>
                            </div>
                            <img id="preview-img" src="" alt="Preview" class="hidden max-h-48 mx-auto rounded-xl object-cover shadow-sm">
                        @endif
                    </div>
                    
                    <p class="text-xs text-slate-400 flex gap-2">
                        <i class="fa-solid fa-circle-info text-slate-300 mt-0.5"></i>
                        <span>Foto ini akan ditampilkan pada halaman depan portal SIPADI untuk publik.</span>
                    </p>
                </div>
 
                {{-- Pengaturan Publikasi Card --}}
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-slate-500"></i>
                        Pengaturan Publikasi
                    </h3>
                    
                    {{-- Toggle: Tampilkan ke Publik --}}
                    <div class="flex items-center justify-between py-2">
                        <div>
                            <h4 class="text-sm font-semibold text-slate-700">Status Publikasi</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5">Tampilkan ke Publik</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="status_toggle" {{ $agenda->status_event === 'terbit' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0e1f30]"></div>
                        </label>
                    </div>
 
                    {{-- Toggle: Sematkan di Atas --}}
                    <div class="flex items-center justify-between py-2 border-t border-slate-100">
                        <div>
                            <h4 class="text-sm font-semibold text-slate-700">Sematkan di Atas</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5">Pin agenda ini</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="tampilkan_beranda" value="1" {{ $agenda->tampilkan_beranda ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0e1f30]"></div>
                        </label>
                    </div>
                </div>
 
                {{-- Info Audit Card --}}
                <div class="bg-[#0e1f30] text-slate-400 rounded-3xl p-6 border border-slate-800 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 text-white">
                        <span class="text-amber-400"><i class="fa-regular fa-folder-open"></i></span>
                        <h3 class="text-sm font-bold">Info Audit</h3>
                    </div>
                    <div class="text-xs">Terakhir diperbarui</div>
                    
                    <div class="space-y-3 pt-2 text-xs">
                        <div class="flex justify-between">
                            <span>Dibuat oleh</span>
                            <span class="font-semibold text-white">
                                {{ $agenda->createdBy?->nama_petugas ?? 'Admin Utama' }}
                            </span>
                        </div>
                        <div class="flex justify-between border-t border-slate-800 pt-2">
                            <span>Tanggal Dibuat</span>
                            <span class="font-semibold text-white">
                                {{ $agenda->created_at ? $agenda->created_at->locale('id')->translatedFormat('d M Y') : '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between border-t border-slate-800 pt-2">
                            <span>Terakhir Update</span>
                            <span class="font-semibold text-white">
                                {{ $agenda->updated_at ? $agenda->updated_at->diffForHumans() : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
 
            </div>
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
        const previewImg = document.getElementById('preview-img');
 
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewImg.classList.remove('hidden');
            if(placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    }
</script>
@endsection
