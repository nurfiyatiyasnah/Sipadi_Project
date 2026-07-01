@extends('layouts.petugas')

@section('title', 'Edit Data Fasilitas')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
            <a href="#" class="hover:text-slate-800">Dashboard</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <a href="{{ route('petugas.fasilitas.index') }}" class="hover:text-slate-800">Fasilitas</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="font-semibold text-slate-800">Edit Fasilitas</span>
        </div>
        <h2 class="text-2xl font-bold text-[#071426]">Edit Data Fasilitas</h2>
        <p class="text-sm text-slate-500 mt-1">Perbarui informasi ketersediaan dan detail fasilitas perpustakaan.</p>
    </div>
</div>

<form action="{{ route('petugas.fasilitas.update', $fasilita->id_fasilitas) }}" method="POST" enctype="multipart/form-data" x-data="fasilitasForm()">
    @csrf
    @method('PUT')

    <div class="mb-6 flex justify-end gap-3">
        <a href="{{ route('petugas.fasilitas.index') }}" class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 bg-white px-5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
            Batal
        </a>
        <button type="submit" class="inline-flex h-10 items-center justify-center rounded-lg bg-[#0e1f30] px-5 text-sm font-medium text-white transition hover:bg-[#1b2e46]">
            Simpan Perubahan
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Informasi Dasar -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4 flex items-center gap-3">
                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                        <i class="fa-solid fa-info text-xs"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Informasi Dasar</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Fasilitas</label>
                            <input type="text" name="nama_fasilitas" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition @error('nama_fasilitas') border-red-500 @enderror" value="{{ old('nama_fasilitas', $fasilita->nama_fasilitas) }}" required>
                            @error('nama_fasilitas') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Kategori</label>
                            <select name="kategori" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition @error('kategori') border-red-500 @enderror" required>
                                <option value="" disabled>Pilih Kategori</option>
                                <option value="Ruangan" {{ old('kategori', $fasilita->kategori) == 'Ruangan' ? 'selected' : '' }}>Ruang Multimedia / Ruangan</option>
                                <option value="Elektronik" {{ old('kategori', $fasilita->kategori) == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                <option value="Peralatan" {{ old('kategori', $fasilita->kategori) == 'Peralatan' ? 'selected' : '' }}>Peralatan</option>
                            </select>
                            @error('kategori') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Lokasi / Gedung</label>
                            <input type="text" name="lokasi" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition @error('lokasi') border-red-500 @enderror" value="{{ old('lokasi', $fasilita->lokasi) }}">
                            @error('lokasi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Deskripsi Fasilitas</label>
                        <textarea name="deskripsi" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $fasilita->deskripsi) }}</textarea>
                        @error('deskripsi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Kapasitas & Sarana -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4 flex items-center gap-3">
                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                        <i class="fa-solid fa-box text-xs"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Kapasitas & Sarana</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Kapasitas Maksimal (Orang/Unit)</label>
                            <div class="relative">
                                <input type="number" name="jumlah_unit" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition @error('jumlah_unit') border-red-500 @enderror" value="{{ old('jumlah_unit', $fasilita->jumlah_unit) }}">
                                <span class="absolute inset-y-0 right-3 flex items-center text-slate-400 text-xs">Peserta/Unit</span>
                            </div>
                            @error('jumlah_unit') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Status Operasional</label>
                            <div class="flex items-center gap-4 mt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="status_fasilitas" value="tersedia" class="text-[#0e1f30] focus:ring-[#0e1f30]" {{ (old('status_fasilitas', $fasilita->status_fasilitas) === 'tersedia' || old('status_fasilitas', $fasilita->status_fasilitas) === 'aktif') ? 'checked' : '' }}>
                                    <span class="text-sm font-medium text-slate-700">Tersedia</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="status_fasilitas" value="maintenance" class="text-[#0e1f30] focus:ring-[#0e1f30]" {{ (old('status_fasilitas', $fasilita->status_fasilitas) === 'maintenance' || old('status_fasilitas', $fasilita->status_fasilitas) === 'perbaikan') ? 'checked' : '' }}>
                                    <span class="text-sm font-medium text-slate-700">Maintenance</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-medium text-slate-700">Fasilitas Pendukung (Centang yang tersedia)</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @php
                                $currentKelengkapan = is_array($fasilita->kelengkapan) ? $fasilita->kelengkapan : [];
                            @endphp
                            @foreach(['AC / Pendingin' => 'AC', 'WiFi High Speed' => 'Wi-Fi', 'Proyektor' => 'Proyektor', 'Sound System' => 'Sound System', 'Papan Tulis' => 'Papan Tulis', 'CCTV' => 'CCTV'] as $label => $val)
                            <label class="flex items-center gap-3 rounded-lg border {{ in_array($val, $currentKelengkapan) ? 'border-blue-500 bg-blue-50' : 'border-slate-200' }} p-3 cursor-pointer hover:bg-slate-50 transition">
                                <input type="checkbox" name="kelengkapan[]" value="{{ $val }}" class="rounded text-[#0e1f30] focus:ring-[#0e1f30]" {{ in_array($val, $currentKelengkapan) ? 'checked' : '' }}>
                                <span class="text-sm text-slate-700 font-medium">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Pembaruan -->
            <div class="rounded-xl border border-[#ffe093] bg-[#fff5d6] p-6 shadow-sm flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-amber-900 text-sm mb-1">Riwayat Pembaruan Terakhir</h4>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Fasilitas ini terakhir diperbarui pada {{ $fasilita->updated_at->format('d F Y, H:i') }} WIB.
                    </p>
                </div>
            </div>

        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            
            <!-- Foto Fasilitas -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4 flex items-center gap-3">
                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                        <i class="fa-solid fa-camera text-xs"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Foto Fasilitas</h3>
                </div>
                <div class="p-6">
                    <div class="relative mb-4 w-full h-40 overflow-hidden rounded-xl border border-slate-200 bg-slate-50 group cursor-pointer" @click="$refs.fileInput.click()">
                        <template x-if="imageUrl">
                            <img :src="imageUrl" class="absolute inset-0 w-full h-full object-cover z-10" />
                        </template>
                        <template x-if="!imageUrl && existingImage">
                            <img :src="existingImage" class="absolute inset-0 w-full h-full object-cover z-10" />
                        </template>

                        <div class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-black/40 opacity-0 transition group-hover:opacity-100">
                            <i class="fa-solid fa-pen text-white text-xl mb-1"></i>
                            <span class="text-xs text-white font-medium">Ubah Foto</span>
                        </div>
                        
                        <div class="absolute inset-0 z-0 flex flex-col items-center justify-center" :class="{'opacity-0': imageUrl || existingImage}">
                            <i class="fa-solid fa-image text-3xl text-slate-300 mb-2"></i>
                            <span class="text-xs text-slate-400">Pilih Foto</span>
                        </div>
                        <input type="file" name="gambar" x-ref="fileInput" class="hidden" accept="image/*" @change="fileChosen">
                    </div>
                    
                    <p class="text-[11px] italic text-slate-500 mb-4 text-center">Format: JPG, PNG. Maksimal 2MB.<br>Resolusi disarankan 1920x1080.</p>

                    <!-- Extra photo slots placeholder -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="flex h-[4.5rem] items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-400 hover:bg-slate-100 transition cursor-pointer"><i class="fa-regular fa-image"></i></div>
                        <div class="flex h-[4.5rem] items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-400 hover:bg-slate-100 transition cursor-pointer"><i class="fa-regular fa-image"></i></div>
                        <div class="flex h-[4.5rem] items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-400 hover:bg-slate-100 transition cursor-pointer"><i class="fa-regular fa-image"></i></div>
                    </div>
                </div>
            </div>

            <!-- Pengaturan Akses -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4 flex items-center gap-3">
                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                        <i class="fa-solid fa-person-walking text-xs"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Pengaturan Akses</h3>
                </div>
                <div class="p-6 space-y-5">
                    
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-slate-700">Metode Peminjaman</label>
                        <select name="metode_peminjaman" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                            <option value="Online Booking" {{ old('metode_peminjaman', $fasilita->metode_peminjaman) == 'Online Booking' ? 'selected' : '' }}>Online Booking</option>
                            <option value="Langsung" {{ old('metode_peminjaman', $fasilita->metode_peminjaman) == 'Langsung' ? 'selected' : '' }}>Datang Langsung</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-slate-700">Durasi Maksimal (Jam)</label>
                        <input type="number" name="durasi_maksimal" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" value="{{ old('durasi_maksimal', $fasilita->durasi_maksimal) }}">
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Tampilkan di Publik</span>
                            <label class="relative inline-flex cursor-pointer items-center">
                                <input type="checkbox" name="tampilkan_publik" class="peer sr-only" {{ old('tampilkan_publik', $fasilita->tampilkan_publik) ? 'checked' : '' }}>
                                <div class="peer h-6 w-11 rounded-full bg-slate-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-[#0e1f30] peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('fasilitasForm', () => ({
        imageUrl: null,
        existingImage: '{{ $fasilita->gambar ? asset('storage/' . $fasilita->gambar) : '' }}',
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
