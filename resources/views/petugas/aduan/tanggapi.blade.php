@extends('layouts.petugas')

@section('title', 'Tanggapi Aduan #' . $aduan->kode_aduan)

@section('content')
<div class="space-y-6">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
        <a href="{{ route('petugas.aduan.index') }}" class="hover:text-slate-600 transition">Aduan</a>
        <i class="fa-solid fa-chevron-right text-[8px]"></i>
        <a href="{{ route('petugas.aduan.show', $aduan) }}" class="hover:text-slate-600 transition">Detail Aduan</a>
        <i class="fa-solid fa-chevron-right text-[8px]"></i>
        <span class="text-slate-600">Tanggapi Aduan</span>
    </div>

    <!-- Page Title -->
    <div>
        <h1 class="font-serif text-3xl font-bold text-slate-800">Tanggapi Aduan</h1>
        <p class="text-sm text-slate-500 mt-1">Berikan respon tindak lanjut atau penyelesaian untuk laporan ini.</p>
    </div>

    <!-- Layout Grid -->
    <div class="grid gap-8 lg:grid-cols-[0.8fr_1.2fr] items-start">
        
        <!-- Left: Detail Preview Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-6">
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3">Detail Aduan</h3>
            
            <div class="space-y-4">
                <!-- Submitter Info -->
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-500 font-bold text-sm">
                        {{ mb_substr($aduan->anggota->nama_lengkap ?? 'A', 0, 2) }}
                    </span>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $aduan->anggota->nama_lengkap }}</p>
                        <p class="text-[10px] text-slate-400 font-semibold">{{ $aduan->anggota->kalangan ?? 'Umum' }}</p>
                    </div>
                </div>

                <!-- Date -->
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tanggal</p>
                    <p class="text-xs text-slate-600 font-medium mt-0.5">
                        {{ $aduan->created_at->locale('id')->translatedFormat('d F Y') }}
                    </p>
                </div>

                <!-- Kategori -->
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Kategori</p>
                    <div class="mt-1">
                        <span class="inline-block bg-slate-100 text-slate-700 border border-slate-200/50 px-2.5 py-0.5 text-[10px] font-bold rounded-full">
                            {{ $aduan->kategori_aduan }}
                        </span>
                    </div>
                </div>

                <!-- Message Box -->
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pesan Aduan</p>
                    <div class="mt-2 bg-slate-50 border border-slate-100 p-4 rounded-xl text-slate-600 text-xs leading-relaxed whitespace-pre-line">
                        {{ $aduan->isi_aduan }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Response Form Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm">
            
            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('petugas.aduan.store-tanggapi', $aduan) }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Status Aduan Radio Buttons -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">Ubah Status Aduan</label>
                    <div class="flex flex-wrap items-center gap-6">
                        <!-- Diproses option -->
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="status_aduan" value="diproses" 
                                class="h-4 w-4 border-slate-300 text-[#0e1f30] focus:ring-[#0e1f30]"
                                {{ old('status_aduan', $aduan->status_aduan) !== 'selesai' ? 'checked' : '' }}>
                            <span class="text-sm font-semibold text-slate-600 group-hover:text-slate-800">Sedang Diproses</span>
                        </label>

                        <!-- Selesai option -->
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="status_aduan" value="selesai" 
                                class="h-4 w-4 border-slate-300 text-[#0e1f30] focus:ring-[#0e1f30]"
                                {{ old('status_aduan', $aduan->status_aduan) === 'selesai' ? 'checked' : '' }}>
                            <span class="text-sm font-semibold text-slate-600 group-hover:text-slate-800">Selesai (Resolved)</span>
                        </label>
                    </div>
                </div>

                <!-- Pesan Tanggapan Textarea -->
                <div>
                    <label for="isi_tanggapan" class="block text-sm font-bold text-slate-700 mb-2">Pesan Tanggapan</label>
                    <div class="border border-slate-200 rounded-2xl overflow-hidden focus-within:border-[#ffd56b] focus-within:ring-2 focus-within:ring-[#ffd56b] transition">
                        <!-- Toolbar mock block just for styling mockup -->
                        <div class="bg-slate-50 border-b border-slate-100 px-4 py-2 flex items-center gap-3 text-slate-400 text-xs">
                            <button type="button" class="hover:text-slate-600 transition"><i class="fa-solid fa-bold"></i></button>
                            <button type="button" class="hover:text-slate-600 transition"><i class="fa-solid fa-italic"></i></button>
                            <button type="button" class="hover:text-slate-600 transition"><i class="fa-solid fa-underline"></i></button>
                            <span class="h-3 w-px bg-slate-200"></span>
                            <button type="button" class="hover:text-slate-600 transition"><i class="fa-solid fa-list-ul"></i></button>
                            <button type="button" class="hover:text-slate-600 transition"><i class="fa-solid fa-list-ol"></i></button>
                        </div>
                        <textarea id="isi_tanggapan" name="isi_tanggapan" rows="6" 
                            class="w-full border-none focus:ring-0 p-4 text-slate-700 text-sm leading-relaxed" 
                            placeholder="Ketik tanggapan Anda di sini..." required>{{ old('isi_tanggapan') }}</textarea>
                    </div>
                </div>

                <!-- Submit buttons -->
                <div class="border-t border-slate-100 pt-6 flex items-center justify-end gap-4">
                    <a href="{{ route('petugas.aduan.show', $aduan) }}" class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 px-6 py-3 text-sm font-bold text-slate-600 transition duration-200">
                        Batal
                    </a>
                    <button type="submit" class="rounded-xl bg-[#0e1f30] hover:bg-[#1b2e46] px-6 py-3 text-sm font-bold text-white shadow-md hover:shadow-lg transition duration-200 flex items-center gap-2">
                        Kirim Tanggapan
                        <i class="fa-regular fa-paper-plane"></i>
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
