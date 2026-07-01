@extends('layouts.public')

@section('title', 'Layanan Pengaduan - SIPADI Bukittinggi')

@section('content')
<div class="py-12 lg:py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-12">
        
        <div class="grid gap-12 lg:grid-cols-[1fr_1.6fr] items-start">
            
            <!-- Left Column: Info & Contact -->
            <div class="space-y-8">
                <div>
                    <h1 class="font-serif text-4xl lg:text-5xl font-bold leading-tight text-[#04241e]">Layanan Pengaduan</h1>
                    <p class="mt-4 text-slate-600 text-base leading-relaxed">
                        Kami menghargai setiap masukan dari Anda untuk meningkatkan kualitas layanan SIPADI Bukittinggi. Silakan lengkapi formulir di samping untuk menyampaikan saran, kritik, atau keluhan Anda.
                    </p>
                </div>

                <!-- Contact Card -->
                <div class="bg-white border border-slate-200/50 rounded-3xl p-8 shadow-sm space-y-6 relative overflow-hidden">
                    <!-- Abstract Background Decoration -->
                    <div class="absolute right-0 top-0 w-24 h-24 bg-slate-50 rounded-bl-full flex items-center justify-center opacity-30 pointer-events-none">
                        <i class="fa-solid fa-headset text-4xl text-slate-300"></i>
                    </div>

                    <h3 class="text-xl font-bold text-[#04241e] border-b border-slate-100 pb-3">Informasi Kontak</h3>
                    
                    <div class="space-y-5">
                        <!-- Alamat -->
                        <div class="flex gap-4 items-start">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-500 mt-0.5 flex-shrink-0">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Alamat</p>
                                <p class="text-sm text-slate-700 mt-1 leading-relaxed">
                                    Jl. Perintis Kemerdekaan No. 1, Bukittinggi
                                </p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex gap-4 items-start">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-500 mt-0.5 flex-shrink-0">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Email</p>
                                <p class="text-sm text-slate-700 mt-1 leading-relaxed">
                                    pengaduan@sipadi.bukittinggi.go.id
                                </p>
                            </div>
                        </div>

                        <!-- Telepon -->
                        <div class="flex gap-4 items-start">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-500 mt-0.5 flex-shrink-0">
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Telepon</p>
                                <p class="text-sm text-slate-700 mt-1 leading-relaxed">
                                    (0752) 123456
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Complaint Form -->
            <div class="bg-white border border-slate-200/50 rounded-[2.5rem] p-8 lg:p-12 shadow-md">
                
                @if ($errors->any())
                    <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('aduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid gap-6 sm:grid-cols-2">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="nama_lengkap" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" 
                                class="w-full rounded-xl bg-slate-50 border-slate-200/80 focus:border-[#04241e] focus:ring-[#04241e] py-3 text-slate-800 transition shadow-sm text-sm" 
                                value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}" required readonly>
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label for="no_telepon" class="block text-sm font-bold text-slate-700 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                            <input type="text" id="no_telepon" name="no_telepon" 
                                class="w-full rounded-xl bg-slate-50 border-slate-200/80 focus:border-[#04241e] focus:ring-[#04241e] py-3 text-slate-800 transition shadow-sm text-sm" 
                                value="{{ old('no_telepon', $anggota->no_telepon ?? '') }}" placeholder="Contoh: 08123456789" required readonly>
                        </div>
                    </div>

                    <!-- Alamat Email -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" 
                            class="w-full rounded-xl bg-slate-50 border-slate-200/80 focus:border-[#04241e] focus:ring-[#04241e] py-3 text-slate-800 transition shadow-sm text-sm" 
                            value="{{ old('email', $anggota->user->email) }}" placeholder="email@contoh.com" required readonly>
                    </div>

                    <!-- Kategori Aduan -->
                    <div>
                        <label for="kategori_aduan" class="block text-sm font-bold text-slate-700 mb-2">Kategori Aduan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="kategori_aduan" name="kategori_aduan" 
                                class="w-full rounded-xl bg-slate-50 border-slate-200/80 focus:border-[#04241e] focus:ring-[#04241e] py-3 text-slate-800 transition shadow-sm text-sm appearance-none pr-10" required>
                                <option value="" disabled selected>Pilih kategori yang sesuai...</option>
                                <option value="Fasilitas Ruang Baca" {{ old('kategori_aduan') === 'Fasilitas Ruang Baca' ? 'selected' : '' }}>Fasilitas Ruang Baca</option>
                                <option value="Koleksi Buku" {{ old('kategori_aduan') === 'Koleksi Buku' ? 'selected' : '' }}>Koleksi Buku</option>
                                <option value="Pelayanan Petugas" {{ old('kategori_aduan') === 'Pelayanan Petugas' ? 'selected' : '' }}>Pelayanan Petugas</option>
                                <option value="Lainnya" {{ old('kategori_aduan') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <!-- Isi Pengaduan -->
                    <div>
                        <label for="isi_aduan" class="block text-sm font-bold text-slate-700 mb-2">Isi Pengaduan <span class="text-red-500">*</span></label>
                        <textarea id="isi_aduan" name="isi_aduan" rows="5" 
                            class="w-full rounded-xl bg-slate-50 border-slate-200/80 focus:border-[#04241e] focus:ring-[#04241e] py-3 text-slate-800 transition shadow-sm text-sm leading-relaxed" 
                            placeholder="Jelaskan detail pengaduan atau masukan Anda di sini..." required>{{ old('isi_aduan') }}</textarea>
                    </div>

                    <!-- Lampiran Pendukung -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Lampiran Pendukung (Opsional)</label>
                        <div class="relative flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl p-6 bg-slate-50 hover:bg-slate-100/50 transition duration-200 group cursor-pointer" x-data="{ fileName: '' }">
                            <input type="file" name="lampiran" id="lampiran" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" 
                                @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                            
                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-white text-slate-400 group-hover:text-slate-600 transition shadow-sm">
                                <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                            </span>
                            
                            <p class="text-sm font-bold text-slate-700 mt-4 text-center">
                                <span x-text="fileName ? fileName : 'Unggah File atau tarik dan lepas'"></span>
                            </p>
                            <p class="text-xs text-slate-400 mt-1 text-center" x-show="!fileName">
                                PNG, JPG, PDF hingga 5MB
                            </p>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="border-t border-slate-100 pt-6 flex items-center justify-end gap-4">
                        <a href="{{ route('landing') }}" class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 px-6 py-3 text-sm font-bold text-slate-600 transition duration-200">
                            Batal
                        </a>
                        <button type="submit" class="rounded-xl bg-[#04241e] hover:bg-[#06342c] px-6 py-3 text-sm font-bold text-white shadow-md hover:shadow-lg transition duration-200 flex items-center gap-2">
                            Kirim Pengaduan
                            <i class="fa-regular fa-paper-plane"></i>
                        </button>
                    </div>
                </form>

            </div>

        </div>

    </div>
</div>
@endsection
