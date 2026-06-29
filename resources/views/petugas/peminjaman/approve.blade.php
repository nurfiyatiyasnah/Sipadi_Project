@extends('layouts.petugas')

@section('title', 'Setujui & Atur Jadwal')

@section('content')
@php use Carbon\Carbon; @endphp
<div class="mx-auto max-w-[1280px]">
    <!-- Breadcrumb -->
    <nav class="flex text-xs font-semibold text-slate-400 mb-6 gap-2 items-center">
        <span class="uppercase tracking-wider">PEMINJAMAN</span>
        <span>/</span>
        <span class="uppercase tracking-wider text-slate-600 font-bold">PERSETUJUAN</span>
    </nav>

    <!-- Header Block -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Setujui & Atur Jadwal</h2>
        <p class="text-sm text-slate-500 mt-1.5">Tentukan jadwal dan lokasi pengambilan untuk pengajuan peminjaman yang telah disetujui.</p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Detail Pengajuan Summary -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-5 pb-3 border-b border-slate-100">DETAIL PENGAJUAN</h3>
                
                <div class="space-y-5">
                    <!-- ID Peminjaman -->
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">ID Peminjaman</span>
                        <span class="block text-base font-extrabold text-slate-800 mt-0.5">{{ $peminjaman->kode_peminjaman }}</span>
                    </div>

                    <!-- Anggota -->
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Anggota</span>
                        <div class="flex items-center gap-3 mt-1.5">
                            @php
                                $initials = '';
                                if ($peminjaman->anggota && $peminjaman->anggota->nama_lengkap) {
                                    $words = explode(' ', $peminjaman->anggota->nama_lengkap);
                                    $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                } else {
                                    $initials = 'A';
                                }
                            @endphp
                            <div class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center font-bold text-xs text-slate-700">
                                {{ $initials }}
                            </div>
                            <div class="leading-none">
                                <span class="block text-sm font-bold text-slate-800">{{ $peminjaman->anggota->nama_lengkap ?? 'Anggota' }}</span>
                                <span class="block text-[11px] text-slate-400 mt-1">Anggota - {{ $peminjaman->anggota->no_anggota ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Buku yang diajukan -->
                    @php
                        $buku = $peminjaman->detailPeminjaman->first()?->buku;
                    @endphp
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">Buku yang Diajukan</span>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex flex-col gap-1">
                            <span class="text-xs font-bold text-slate-800 line-clamp-2">
                                {{ $buku->judul ?? 'Buku' }}
                            </span>
                            <span class="text-[10px] text-slate-500 mt-1 font-semibold">
                                {{ $buku->penulis ?? '-' }} &bull; ISBN: {{ $buku->isbn ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Scheduling Form -->
        <div class="lg:col-span-8 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-700">
                    <i class="fa-regular fa-calendar-check"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Form Jadwal Pengambilan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Lengkapi informasi di bawah untuk menginformasikan peminjam.</p>
                </div>
            </div>

            <!-- Validation Errors if any -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('petugas.peminjaman.approve', $peminjaman->id_peminjaman) }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Tanggal Pengambilan -->
                    <div class="space-y-2">
                        <label for="tanggal_pengambilan" class="block text-xs font-bold text-slate-700">
                            Tanggal Pengambilan <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="tanggal_pengambilan" id="tanggal_pengambilan" 
                               value="{{ old('tanggal_pengambilan', $peminjaman->jadwalPengambilan?->tanggal_pengambilan ? Carbon::parse($peminjaman->jadwalPengambilan->tanggal_pengambilan)->format('Y-m-d') : date('Y-m-d')) }}" 
                               class="w-full text-sm rounded-xl border border-slate-200 bg-slate-50/70 p-3 text-slate-700 outline-none transition focus:border-slate-300 focus:bg-white" 
                               required>
                    </div>

                    <!-- Jam Pengambilan -->
                    <div class="space-y-2">
                        <label for="jam_pengambilan" class="block text-xs font-bold text-slate-700">
                            Jam Pengambilan <span class="text-rose-500">*</span>
                        </label>
                        @php
                            $jam = '09:00';
                            if ($peminjaman->jadwalPengambilan?->jam_mulai) {
                                $jam = date('H:i', strtotime($peminjaman->jadwalPengambilan->jam_mulai));
                            }
                        @endphp
                        <input type="time" name="jam_pengambilan" id="jam_pengambilan" 
                               value="{{ old('jam_pengambilan', $jam) }}" 
                               class="w-full text-sm rounded-xl border border-slate-200 bg-slate-50/70 p-3 text-slate-700 outline-none transition focus:border-slate-300 focus:bg-white" 
                               required>
                    </div>
                </div>

                <!-- Lokasi Pengambilan -->
                <div class="space-y-2">
                    <label for="lokasi_pengambilan" class="block text-xs font-bold text-slate-700">
                        Lokasi Pengambilan <span class="text-rose-500">*</span>
                    </label>
                    @php
                        $lokasi = old('lokasi_pengambilan', $peminjaman->jadwalPengambilan?->lokasi_pengambilan ?? 'Meja Sirkulasi Lantai 1');
                    @endphp
                    <select name="lokasi_pengambilan" id="lokasi_pengambilan" 
                            class="w-full text-sm rounded-xl border border-slate-200 bg-slate-50/70 p-3 text-slate-700 outline-none transition focus:border-slate-300 focus:bg-white" 
                            required>
                        <option value="Meja Sirkulasi Lantai 1" {{ $lokasi === 'Meja Sirkulasi Lantai 1' ? 'selected' : '' }}>Meja Sirkulasi Lantai 1</option>
                        <option value="Meja Layanan Lantai 1" {{ $lokasi === 'Meja Layanan Lantai 1' ? 'selected' : '' }}>Meja Layanan Lantai 1</option>
                        <option value="Meja Layanan Lantai 2" {{ $lokasi === 'Meja Layanan Lantai 2' ? 'selected' : '' }}>Meja Layanan Lantai 2</option>
                    </select>
                </div>

                <!-- Catatan Tambahan (Opsional) -->
                <div class="space-y-2">
                    <label for="pesan" class="block text-xs font-bold text-slate-700">
                        Catatan Tambahan (Opsional)
                    </label>
                    <textarea name="pesan" id="pesan" rows="4" 
                              placeholder="Contoh: Harap membawa kartu mahasiswa asli saat pengambilan..." 
                              class="w-full text-sm rounded-xl border border-slate-200 bg-slate-50/70 p-3 text-slate-700 outline-none transition focus:border-slate-300 focus:bg-white">{{ old('pesan', $peminjaman->jadwalPengambilan?->pesan) }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                    <a href="{{ route('petugas.peminjaman.show', $peminjaman->id_peminjaman) }}" 
                       class="px-5 py-3 border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-sm rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-5 py-3 bg-[#0e1f30] text-white hover:bg-[#122b42] font-bold text-sm rounded-xl transition shadow-sm flex items-center gap-2">
                        <i class="fa-regular fa-paper-plane"></i> Simpan & Kirim Notifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
