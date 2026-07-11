@extends('layouts.petugas')

@section('title', 'Tambah Fasilitas Baru')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
            <a href="#" class="hover:text-slate-800">SIPADI</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <a href="{{ route('petugas.fasilitas.index') }}" class="hover:text-slate-800">Fasilitas</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="font-semibold text-slate-800">Tambah Fasilitas Baru</span>
        </div>
        <h2 class="text-2xl font-bold text-[#071426]">Tambah Fasilitas Baru</h2>
        <p class="text-sm text-slate-500 mt-1">Lengkapi detail informasi untuk menambahkan fasilitas perpustakaan baru.</p>
    </div>
</div>

<form action="{{ route('petugas.fasilitas.store') }}" method="POST" enctype="multipart/form-data" x-data="fasilitasForm()">
    @csrf

    <div class="mb-6 flex justify-end gap-3">
        <a href="{{ route('petugas.fasilitas.index') }}" class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 bg-white px-5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
            Batal
        </a>
        <button type="submit" class="inline-flex h-10 items-center justify-center rounded-lg bg-[#0e1f30] px-5 text-sm font-medium text-white transition hover:bg-[#1b2e46]">
            Simpan Fasilitas
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Informasi Umum -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4 flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-slate-400"></i>
                    <h3 class="font-semibold text-slate-800">Informasi Umum</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Fasilitas</label>
                            <input type="text" name="nama_fasilitas" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition @error('nama_fasilitas') border-red-500 @enderror" placeholder="Contoh: Ruang Multimedia" value="{{ old('nama_fasilitas') }}" required>
                            @error('nama_fasilitas') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Kategori</label>
                            <select name="kategori" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition @error('kategori') border-red-500 @enderror" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="Ruangan" {{ old('kategori') == 'Ruangan' ? 'selected' : '' }}>Ruang Belajar / Ruangan</option>
                                <option value="Elektronik" {{ old('kategori') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                <option value="Peralatan" {{ old('kategori') == 'Peralatan' ? 'selected' : '' }}>Peralatan</option>
                            </select>
                            @error('kategori') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Deskripsi Singkat</label>
                        <textarea name="deskripsi" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition @error('deskripsi') border-red-500 @enderror" placeholder="Jelaskan kegunaan dan kapasitas fasilitas ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Spesifikasi & Kelengkapan -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4 flex items-center gap-3">
                    <i class="fa-solid fa-list-check text-slate-400"></i>
                    <h3 class="font-semibold text-slate-800">Spesifikasi & Kelengkapan</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Kapasitas (Orang/Unit)</label>
                            <input type="number" name="jumlah_unit" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition @error('jumlah_unit') border-red-500 @enderror" placeholder="0" value="{{ old('jumlah_unit') }}">
                            @error('jumlah_unit') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Lokasi Gedung</label>
                            <input type="text" name="lokasi" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition @error('lokasi') border-red-500 @enderror" placeholder="Lantai 2, Sayap Timur" value="{{ old('lokasi') }}">
                            @error('lokasi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Status Operasional</label>
                            <div class="flex items-center gap-4 mt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="status_fasilitas" value="tersedia" class="text-[#0e1f30] focus:ring-[#0e1f30]" checked>
                                    <span class="text-sm text-slate-700">Aktif/Tersedia</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="status_fasilitas" value="perbaikan" class="text-[#0e1f30] focus:ring-[#0e1f30]">
                                    <span class="text-sm text-slate-700">Perbaikan</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-medium text-slate-700">Kelengkapan Fasilitas</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach(['AC', 'Wi-Fi', 'Proyektor', 'Sound System'] as $item)
                            <label class="flex items-center gap-3 rounded-lg border border-slate-200 p-3 cursor-pointer hover:bg-slate-50 transition">
                                <input type="checkbox" name="kelengkapan[]" value="{{ $item }}" class="rounded text-[#0e1f30] focus:ring-[#0e1f30]">
                                <span class="text-sm text-slate-700">{{ $item }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            
            <!-- Galeri Foto -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4 flex items-center gap-3">
                    <i class="fa-regular fa-images text-slate-400"></i>
                    <h3 class="font-semibold text-slate-800">Galeri Foto</h3>
                </div>
                <div class="p-6">
                    <div class="relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-6 text-center hover:bg-slate-100 transition cursor-pointer" @click="$refs.fileInput.click()">
                        
                        <template x-if="imageUrl">
                            <img :src="imageUrl" class="absolute inset-0 w-full h-full object-cover rounded-xl z-10" />
                        </template>

                        <div class="z-20 pointer-events-none" :class="{'opacity-0': imageUrl}">
                            <i class="fa-solid fa-camera-retro text-4xl text-slate-400 mb-3"></i>
                            <h4 class="text-sm font-semibold text-slate-700">Unggah Foto Utama</h4>
                            <p class="text-xs text-slate-500 mt-1">Format JPG, PNG atau WEBP.<br>Maksimal 2MB.</p>
                        </div>
                        <input type="file" name="gambar" x-ref="fileInput" class="hidden" accept="image/*" @change="fileChosen">
                    </div>
                    @error('gambar') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror

                    <!-- Extra photo slots placeholder like in UI -->
                    <div class="grid grid-cols-3 gap-3 mt-4">
                        <div class="flex h-20 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-400"><i class="fa-solid fa-plus"></i></div>
                        <div class="flex h-20 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-400"><i class="fa-solid fa-plus"></i></div>
                        <div class="flex h-20 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-400"><i class="fa-solid fa-plus"></i></div>
                    </div>
                </div>
            </div>

            <!-- Pengaturan Publikasi -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4 flex items-center gap-3">
                    <i class="fa-solid fa-gear text-slate-400"></i>
                    <h3 class="font-semibold text-slate-800">Pengaturan Publikasi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Tampilkan di Website</span>
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="checkbox" name="tampilkan_publik" value="1" class="peer sr-only" checked>
                            <div class="peer h-6 w-11 rounded-full bg-slate-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-[#0e1f30] peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none"></div>
                        </label>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Aktifkan Reservasi</span>
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="checkbox" name="aktifkan_reservasi" value="1" x-model="reservasiAktif" class="peer sr-only">
                            <div class="peer h-6 w-11 rounded-full bg-slate-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-[#0e1f30] peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none"></div>
                        </label>
                    </div>

                    <div x-show="reservasiAktif" x-collapse class="pt-3 border-t border-slate-100 space-y-4 mt-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-slate-700">Metode Peminjaman</label>
                            <select name="metode_peminjaman" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                                <option value="Online Booking">Online Booking</option>
                                <option value="Langsung">Datang Langsung</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-slate-700">Durasi Maksimal (Jam)</label>
                            <input type="number" name="durasi_maksimal" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" placeholder="Misal: 3">
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-400 leading-relaxed mt-4">
                        *Pastikan semua data telah sesuai sebelum disimpan. Data fasilitas akan langsung tersedia bagi publik jika opsi "Tampilkan di Website" aktif.
                    </p>
                </div>
            </div>

        </div>
    </div>
</form>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('fasilitasForm', () => ({
        imageUrl: null,
        reservasiAktif: false,
        fileChosen(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = e => this.imageUrl = e.target.result;
        }
    }))
})
</script>
@endsection
