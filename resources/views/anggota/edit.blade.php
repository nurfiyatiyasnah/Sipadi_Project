@extends('layouts.petugas')
@section('title', 'Update Data Anggota')

@section('content')
<div class="mx-auto max-w-[1280px]">
    <!-- Breadcrumbs -->
    <div class="mb-4 text-xs font-semibold text-slate-500 flex items-center gap-2">
        <span class="hover:text-slate-700">Dashboard</span>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <a href="{{ route('petugas.anggota.index') }}" class="hover:text-slate-700">Anggota</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="text-slate-800">Update Data Anggota</span>
    </div>

    <!-- Heading -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-slate-850">Update Data Anggota</h2>
        <p class="text-sm text-slate-500 mt-1">Perbarui informasi profil dan status akun anggota.</p>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-sm">
            <div class="flex items-center gap-2 mb-2 font-bold">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Terdapat kesalahan pengisian data:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Container -->
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100">
        <form method="POST" action="{{ route('petugas.anggota.update', $anggota->id_anggota) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Upper Row: Photo & Read-only NIK / ID Anggota -->
            <div class="flex flex-col md:flex-row gap-8 items-start pb-8 border-b border-slate-100 mb-8">
                <!-- Profile Photo Upload -->
                <div class="flex flex-col items-center text-center w-full md:w-auto md:shrink-0" x-data="{ photoPreview: null }">
                    <div class="relative mb-3">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" alt="Preview Foto" class="h-32 w-32 rounded-full object-cover border border-slate-200 shadow-md">
                        </template>
                        <template x-if="!photoPreview">
                            @if ($anggota->foto)
                                <img src="{{ asset('storage/' . $anggota->foto) }}" alt="{{ $anggota->nama_lengkap }}" class="h-32 w-32 rounded-full object-cover border border-slate-200 shadow-md">
                            @else
                                <div class="h-32 w-32 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-400 text-2xl border border-slate-200 shadow-md">
                                    {{ strtoupper(substr($anggota->nama_lengkap, 0, 2)) }}
                                </div>
                            @endif
                        </template>
                    </div>

                    <!-- Hidden input file -->
                    <input type="file" name="foto" id="foto-input" class="hidden" accept="image/*" 
                        onchange="
                            const file = this.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const previewImg = document.createElement('img');
                                    // Set data context or simple JS element modification
                                    const img = document.querySelector('[alt=\'Preview Foto\']') || document.querySelector('[alt=\'{{ $anggota->nama_lengkap }}\']') || document.querySelector('.rounded-full');
                                    if(img) img.src = e.target.result;
                                }
                                reader.readAsDataURL(file);
                            }
                        ">
                    
                    <p class="text-[10px] text-slate-400 leading-normal max-w-[180px] mb-3">Allowed format: JPG, PNG. Max size: 2MB</p>
                    <button type="button" onclick="document.getElementById('foto-input').click()" class="text-xs font-bold text-[#7c6312] hover:text-[#634e0e] hover:underline transition">
                        Upload Foto Baru
                    </button>
                </div>

                <!-- Read-only inputs -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 flex-1 w-full">
                    <!-- NIK (Read-only) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" value="{{ $anggota->nik }}" disabled class="w-full rounded-xl border border-slate-200 bg-slate-100 py-3 px-4 text-sm text-slate-500 cursor-not-allowed outline-none">
                        <span class="block text-[10px] text-slate-400 mt-1.5">NIK tidak dapat diubah setelah terdaftar.</span>
                    </div>

                    <!-- ID Anggota (Read-only) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">ID Anggota / No. Anggota</label>
                        <input type="text" value="{{ $anggota->no_anggota }}" disabled class="w-full rounded-xl border border-slate-200 bg-slate-100 py-3 px-4 text-sm text-slate-500 cursor-not-allowed outline-none">
                    </div>
                </div>
            </div>

            <!-- Lower Row: Form Inputs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Input Fields -->
                <div class="space-y-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama_lengkap" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 px-4 text-sm text-slate-800 outline-none transition focus:border-slate-300 focus:bg-white">
                    </div>

                    <!-- Email Aktif -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Email Aktif <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $anggota->user?->email) }}" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 px-4 text-sm text-slate-800 outline-none transition focus:border-slate-300 focus:bg-white">
                    </div>

                    <!-- No Telepon / WhatsApp -->
                    <div>
                        <label for="no_telepon" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">No. Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                        <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', $anggota->no_telepon) }}" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 px-4 text-sm text-slate-800 outline-none transition focus:border-slate-300 focus:bg-white">
                    </div>
                </div>

                <!-- Right Input Fields -->
                <div class="space-y-6">
                    <!-- Alamat Lengkap -->
                    <div>
                        <label for="alamat" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                        <textarea name="alamat" id="alamat" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 px-4 text-sm text-slate-800 outline-none transition focus:border-slate-300 focus:bg-white leading-relaxed">{{ old('alamat', $anggota->alamat) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Status Anggota</span>
                            <div class="min-h-[52px] rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $statusAnggota['class'] }}">
                                    {{ $statusAnggota['label'] }}
                                </span>
                                <p class="mt-2 text-[10px] leading-normal text-slate-400">{{ $statusAnggota['description'] }}</p>
                            </div>
                        </div>

                        <div>
                            <span class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Status Sanksi</span>
                            <div class="min-h-[52px] rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $statusSanksi['class'] }}">
                                    {{ $statusSanksi['label'] }}
                                </span>
                                <p class="mt-2 text-[10px] leading-normal text-slate-400">{{ $statusSanksi['description'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Buttons -->
            <div class="mt-10 flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                <a href="{{ route('petugas.anggota.show', $anggota->id_anggota) }}" class="flex items-center justify-center px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition shadow-sm">
                    Batal
                </a>
                <button type="submit" class="flex items-center justify-center px-6 py-3 rounded-xl bg-[#7c6312] text-white font-bold text-sm hover:bg-[#634e0e] transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
