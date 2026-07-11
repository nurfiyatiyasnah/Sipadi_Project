@extends('layouts.petugas')

@section('title', 'Detail Pengembalian & Sanksi')

@section('content')
@php use Carbon\Carbon; @endphp
<div class="mx-auto max-w-[1280px]">
    <!-- Breadcrumb -->
    <nav class="flex text-xs font-semibold text-slate-400 mb-6 gap-2 items-center">
        <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-600 transition">Dashboard</a>
        <span>&gt;</span>
        <a href="{{ route('petugas.pengembalian.index') }}" class="hover:text-slate-600 transition">Pengembalian</a>
        <span>&gt;</span>
        <span class="text-slate-600 font-bold">Detail Pengembalian & Sanksi</span>
    </nav>

    <!-- Header Block -->
    <form id="confirm-form" action="{{ route('petugas.pengembalian.store', $peminjaman->id_peminjaman) }}" method="POST">
        @csrf
        <!-- Pass input data as hidden inputs -->
        <input type="hidden" name="tanggal_pengembalian" value="{{ $tanggalKembali->toDateString() }}">
        <input type="hidden" name="hari_terlambat" value="{{ $hariTerlambat }}">
        <input type="hidden" name="keadaan_buku" value="{{ $keadaanBuku }}">
        <input type="hidden" name="catatan_kondisi" value="{{ $catatanKondisi }}">
        <input type="hidden" name="photo_path" value="{{ $photoPath }}">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Detail Pengembalian & Sanksi</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-650">
                        ● Menunggu Konfirmasi
                    </span>
                </div>
                <p class="text-sm text-slate-500 mt-1">Tinjau detail pengembalian buku, keterlambatan, kondisi buku, dan sanksi sebelum menyimpan data pengembalian.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('petugas.pengembalian.proses-form', $peminjaman->id_peminjaman) }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-sm rounded-xl transition shadow-sm">
                    Kembali
                </a>
                <button type="submit" class="px-5 py-2.5 bg-[#0e1f30] text-white hover:bg-[#122b42] font-bold text-sm rounded-xl transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-sm"></i> Konfirmasi Pengembalian
                </button>
            </div>
        </div>
    </form>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column: Member Info, Book info, Book Status (col-span-5) -->
        <div class="lg:col-span-5 space-y-8">
            <!-- Informasi Anggota Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-700">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-850">Informasi Anggota</h3>
                </div>

                @php
                    $anggota = $peminjaman->anggota;
                    $initials = collect(explode(' ', $anggota?->nama_lengkap ?? 'A'))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                @endphp
                <div class="flex items-start gap-4">
                    <div class="h-14 w-14 rounded-full bg-slate-150 border border-slate-250 flex items-center justify-center text-base font-extrabold text-slate-650 shadow-sm flex-shrink-0">
                        {{ strtoupper($initials) }}
                    </div>
                    <div class="space-y-3 flex-1">
                        <div>
                            <h4 class="text-base font-bold text-slate-850">{{ $anggota?->nama_lengkap }}</h4>
                            <p class="text-xs text-slate-400 font-semibold mt-0.5">ID: {{ $anggota?->no_anggota }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 mt-1.5 bg-blue-50 text-blue-650 rounded-md text-[10px] font-bold">
                                Anggota
                            </span>
                        </div>
                        <div class="pt-2 space-y-1.5 text-xs text-slate-600">
                            <div class="flex justify-between">
                                <span class="text-slate-400 font-semibold">Email</span>
                                <span class="font-bold text-slate-750">{{ $anggota?->user?->email ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400 font-semibold">Telepon</span>
                                <span class="font-bold text-slate-750">{{ $anggota?->no_telepon ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buku Yang Dikembalikan Card -->
            @php
                $firstDetail = $peminjaman->detailPeminjaman->first();
                $buku = $firstDetail?->buku;
                $firstEksemplar = $buku?->eksemplar?->first();
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-700">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-850">Buku Yang Dikembalikan</h3>
                </div>

                <div class="flex gap-4">
                    <x-book-cover :book="$buku" class="h-28 w-20 rounded" icon-class="text-2xl" />
                    <div class="space-y-2 flex-1">
                        <div>
                            <span class="block text-[9px] font-extrabold uppercase tracking-wider text-slate-400">Katalog: {{ $firstEksemplar?->kode_eksemplar ?? '-' }}</span>
                            <h4 class="text-sm font-bold text-slate-850 mt-0.5 leading-tight">{{ $buku?->judul }}</h4>
                            <p class="text-xs text-slate-450 mt-0.5">{{ $buku?->penulis }}</p>
                        </div>
                        <div class="pt-1.5 space-y-1 text-[11px] text-slate-600">
                            <div class="flex justify-between">
                                <span class="text-slate-400 font-semibold">ISBN</span>
                                <span class="font-semibold text-slate-750">{{ $buku?->isbn ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400 font-semibold">Kategori</span>
                                <span class="font-semibold text-slate-750">{{ $buku?->kategori?->nama_kategori ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Buku Saat Ini Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-700">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-850">Status Buku Saat Ini</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-slate-400 uppercase">Kondisi Fisik</span>
                        @if($keadaanBuku === 'Baik')
                            <span class="px-2.5 py-0.5 rounded bg-emerald-50 text-emerald-600 font-bold text-xs">
                                Baik
                            </span>
                        @elseif($keadaanBuku === 'Rusak Ringan')
                            <span class="px-2.5 py-0.5 rounded bg-amber-50 text-amber-600 font-bold text-xs">
                                Rusak Ringan
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded bg-rose-50 text-rose-600 font-bold text-xs">
                                {{ $keadaanBuku }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-slate-400 uppercase">Status Peminjaman</span>
                        @if($keadaanBuku === 'Baik')
                            <span class="px-2.5 py-0.5 rounded bg-emerald-50 text-emerald-600 font-bold text-xs">
                                Dapat Dipinjam
                            </span>
                        @elseif($keadaanBuku === 'Rusak Ringan' || $keadaanBuku === 'Rusak Berat')
                            <span class="px-2.5 py-0.5 rounded bg-amber-50 text-amber-600 font-bold text-xs">
                                Perlu Perbaikan
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded bg-rose-50 text-rose-600 font-bold text-xs">
                                Dihapus dari Koleksi
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Detail Laporan & Sanksi (col-span-7) -->
        <div class="lg:col-span-7 space-y-8">
            <!-- Detail Laporan Pengembalian Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-700">
                        <i class="fa-regular fa-file-lines"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-850">Detail Laporan Pengembalian</h3>
                </div>

                <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm mb-6">
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Kode Peminjaman</span>
                        <span class="block font-bold text-slate-750 mt-0.5">{{ $peminjaman->kode_peminjaman }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Tanggal Pinjam</span>
                        <span class="block font-bold text-slate-750 mt-0.5">
                            {{ $peminjaman->tanggal_diambil ? $peminjaman->tanggal_diambil->locale('id')->translatedFormat('d M Y, H:i') : '-' }} WIB
                        </span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Tanggal Seharusnya Kembali</span>
                        <span class="block font-bold text-slate-750 mt-0.5">
                            {{ $peminjaman->tanggal_jatuh_tempo ? $peminjaman->tanggal_jatuh_tempo->locale('id')->translatedFormat('d M Y') : '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Tanggal Kembali Aktual</span>
                        <span class="block font-bold text-slate-750 mt-0.5">
                            {{ $tanggalKembali->locale('id')->translatedFormat('d M Y') }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Keterlambatan</span>
                        <span class="block font-bold mt-0.5 {{ $hariTerlambat > 0 ? 'text-rose-600 font-extrabold' : 'text-slate-750' }}">
                            {{ $hariTerlambat > 0 ? $hariTerlambat . ' Hari' : 'Tidak Terlambat' }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Keadaan Buku</span>
                        <span class="block font-bold mt-0.5 {{ $keadaanBuku !== 'Baik' ? 'text-rose-650' : 'text-slate-750' }}">
                            {{ $keadaanBuku }}
                        </span>
                    </div>
                </div>

                @if($catatanKondisi)
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-xs mb-6">
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">Catatan Kerusakan</span>
                        <p class="text-slate-700 font-semibold leading-relaxed">{{ $catatanKondisi }}</p>
                    </div>
                @endif

                @if($photoPath)
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">Bukti Foto</span>
                        <div class="h-36 w-36 rounded-xl border border-slate-200 overflow-hidden shadow-sm bg-slate-50">
                            <img src="{{ asset('storage/' . $photoPath) }}" alt="Bukti Foto" class="w-full h-full object-cover">
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sanksi Keterlambatan Card -->
            @if($hariTerlambat > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                                <i class="fa-solid fa-gavel"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-850">Sanksi Keterlambatan</h3>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100">
                            Sanksi Aktif
                        </span>
                    </div>

                    <!-- Date range display -->
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-100 flex flex-col gap-1">
                            <span class="text-[9px] font-extrabold uppercase tracking-wider text-slate-400">TANGGAL SEHARUSNYA KEMBALI</span>
                            <span class="text-sm font-bold text-slate-750">
                                {{ $peminjaman->tanggal_jatuh_tempo->locale('id')->translatedFormat('d M Y') }}
                            </span>
                        </div>
                        <div class="p-4 rounded-xl bg-rose-50/20 border border-rose-100/50 flex flex-col gap-1">
                            <span class="text-[9px] font-extrabold uppercase tracking-wider text-rose-600">TANGGAL KEMBALI NYATA</span>
                            <span class="text-sm font-bold text-rose-700">
                                {{ $tanggalKembali->locale('id')->translatedFormat('d M Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Calculation card -->
                    <div class="bg-rose-50/10 border border-rose-100/50 rounded-xl p-5 mb-6">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Rincian Perhitungan Sistem</span>
                        
                        <div class="flex justify-between items-center mt-3 pt-3 border-t border-rose-100/30">
                            <span class="text-xs font-bold text-slate-500">Selisih Hari (Terlambat)</span>
                            <span class="text-sm font-bold text-rose-600">{{ $hariTerlambat }} Hari</span>
                        </div>

                        <div class="mt-4 pt-4 border-t border-rose-150/30">
                            <span class="text-xs font-bold text-slate-500">Aturan Sanksi Denda/Skorsing (1 Hari Telat = {{ $multiplier }} Hari Skorsing)</span>
                            <h4 class="text-lg font-black text-rose-700 mt-1">
                                {{ $sanksiHari }} Hari Tidak Bisa Meminjam
                            </h4>
                            <p class="text-[10px] text-slate-400 mt-1">Otomatis diterapkan pada akun anggota</p>
                        </div>
                    </div>

                    <!-- Penalty warning card -->
                    @php
                        $tanggalSelesaiSanksi = $tanggalKembali->copy()->addDays($sanksiHari);
                        $tanggalPulih = $tanggalSelesaiSanksi->copy()->addDay();
                    @endphp
                    <div class="p-4 rounded-xl bg-blue-50/40 border border-blue-100 flex gap-3.5">
                        <i class="fa-solid fa-circle-info text-blue-500 text-base mt-0.5"></i>
                        <div>
                            <h5 class="text-xs font-bold text-blue-850">Periode Sanksi</h5>
                            <p class="text-[11px] text-slate-600 mt-1 leading-relaxed">
                                Anggota tidak dapat melakukan peminjaman buku baru mulai <strong>{{ $tanggalKembali->locale('id')->translatedFormat('d M Y') }}</strong> hingga <strong>{{ $tanggalSelesaiSanksi->locale('id')->translatedFormat('d M Y') }}</strong>. Hak pinjam akan otomatis pulih pada <strong>{{ $tanggalPulih->locale('id')->translatedFormat('d M Y') }}</strong>.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
