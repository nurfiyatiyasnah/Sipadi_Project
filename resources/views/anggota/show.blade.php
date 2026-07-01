@extends('layouts.petugas')
@section('title', 'Detail Anggota')

@section('content')
<div class="mx-auto max-w-[1280px]">
    <!-- Breadcrumbs -->
    <div class="mb-4 text-xs font-semibold text-slate-500 flex items-center gap-2">
        <span class="hover:text-slate-700">Dashboard</span>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <a href="{{ route('petugas.anggota.index') }}" class="hover:text-slate-700">Anggota</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="text-slate-800">Detail Anggota</span>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
        <div class="mb-6 flex items-center justify-between p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold transition" id="success-alert">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="document.getElementById('success-alert').remove()" class="text-emerald-500 hover:text-emerald-800 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <!-- Header Actions Section -->
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-extrabold text-slate-850">Detail Anggota</h2>
        <div class="flex items-center gap-3">
            <a href="{{ route('petugas.anggota.index') }}" class="flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-sm hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('petugas.anggota.edit', $anggota->id_anggota) }}" class="flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#7c6312] text-white font-bold text-sm hover:bg-[#634e0e] transition shadow-sm">
                <i class="fa-solid fa-pencil"></i> Update Data
            </a>
        </div>
    </div>

    <!-- Main Grid Profile Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Left Panel: Profile & Quick Stats -->
        <div class="lg:col-span-1 bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col items-center justify-between min-h-[440px]">
            <div class="flex flex-col items-center text-center w-full">
                <!-- Avatar Photo -->
                <div class="relative w-32 h-32 mx-auto mb-5 shrink-0 block">
                    @if ($anggota->foto)
                        <img src="{{ asset('storage/' . $anggota->foto) }}" alt="{{ $anggota->nama_lengkap }}" class="h-32 w-32 rounded-full object-cover border-4 border-slate-50 shadow-md aspect-square" style="width: 128px; height: 128px; max-width: 128px; max-height: 128px; object-fit: cover; border-radius: 9999px;">
                    @else
                        <div class="h-32 w-32 rounded-full bg-slate-100 flex items-center justify-center font-black text-slate-400 text-3xl border-4 border-slate-50 shadow-md" style="width: 128px; height: 128px; max-width: 128px; max-height: 128px; border-radius: 9999px;">
                            {{ strtoupper(substr($anggota->nama_lengkap, 0, 2)) }}
                        </div>
                    @endif
                    @if (strtolower($anggota->status_anggota) === 'aktif')
                        <span class="absolute bottom-1 right-1 h-6 w-6 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center text-white text-[10px]" title="Akun Aktif" style="bottom: 4px; right: 4px;">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </span>
                    @endif
                </div>

                <!-- Name & NIK -->
                <h3 class="text-xl font-bold text-slate-800">{{ $anggota->nama_lengkap }}</h3>
                <div class="mt-1">
                    <span class="inline-flex items-center text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                        NIK: {{ $anggota->nik }}
                    </span>
                </div>

                <!-- Badges -->
                <div class="flex flex-wrap justify-center gap-2 mt-4">
                    <!-- Status Badge -->
                    @if (strtolower($anggota->status_anggota) === 'aktif')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                            <span class="h-1.5 w-1.5 rounded-full bg-rose-550"></span>
                            Nonaktif
                        </span>
                    @endif

                    <!-- Sanksi Badge -->
                    @php
                        $activeSanksi = $anggota->sanksi->where('status_sanksi', 'aktif')->first();
                        $sanksiText = 'Bebas Sanksi';
                        $sanksiClass = 'bg-slate-100 text-slate-600';
                        if ($activeSanksi) {
                            if (stripos($activeSanksi->jenis_sanksi, 'Blokir') !== false) {
                                $sanksiText = 'Diblokir';
                                $sanksiClass = 'bg-rose-50 text-rose-600 border border-rose-100';
                            } else {
                                $sanksiText = $activeSanksi->jenis_sanksi;
                                $sanksiClass = 'bg-amber-50 text-amber-700 border border-amber-100';
                            }
                        }
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $sanksiClass }}">
                        {{ $sanksiText }}
                    </span>
                </div>
            </div>

            <!-- Stats Boxes -->
            <div class="w-full mt-8 border-t border-slate-100 pt-5">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-3">STATISTIK PEMINJAMAN</p>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-center">
                        <h4 class="text-2xl font-black text-slate-800">{{ $totalPinjam }}</h4>
                        <p class="text-[10px] text-slate-400 font-bold mt-1">Buku Dipinjam</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-center">
                        <h4 class="text-2xl font-black text-slate-800">{{ $totalTerlambat }}</h4>
                        <p class="text-[10px] text-slate-400 font-bold mt-1">Keterlambatan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Personal Details -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
            <div class="h-full flex flex-col justify-between">
                <!-- Panel Header -->
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-6">
                    <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-600">
                        <i class="fa-solid fa-address-card text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-850">Informasi Pribadi</h3>
                        <p class="text-xs text-slate-400">Data profil dan kontak akademik anggota</p>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 flex-1 py-2">
                    <!-- Email -->
                    <div class="flex items-start gap-4">
                        <div class="h-10 w-10 rounded-xl bg-blue-50/60 flex items-center justify-center text-blue-600 shrink-0 border border-blue-100/30">
                            <i class="fa-regular fa-envelope text-base"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">EMAIL AKADEMIK</span>
                            <span class="text-sm font-semibold text-slate-700 break-all">{{ $anggota->user?->email }}</span>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-start gap-4">
                        <div class="h-10 w-10 rounded-xl bg-emerald-50/60 flex items-center justify-center text-emerald-600 shrink-0 border border-emerald-100/30">
                            <i class="fa-solid fa-phone text-base"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">NOMOR TELEPON</span>
                            <span class="text-sm font-semibold text-slate-700">{{ $anggota->no_telepon ?: '-' }}</span>
                        </div>
                    </div>

                    <!-- Domisili Address -->
                    <div class="md:col-span-2 flex items-start gap-4">
                        <div class="h-10 w-10 rounded-xl bg-purple-50/60 flex items-center justify-center text-purple-600 shrink-0 border border-purple-100/30">
                            <i class="fa-solid fa-location-dot text-base"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">ALAMAT DOMISILI</span>
                            <span class="text-sm font-semibold text-slate-700 leading-relaxed">{{ $anggota->alamat ?: '-' }}</span>
                        </div>
                    </div>

                    <!-- Join Date -->
                    <div class="flex items-start gap-4">
                        <div class="h-10 w-10 rounded-xl bg-amber-50/60 flex items-center justify-center text-amber-600 shrink-0 border border-amber-100/30">
                            <i class="fa-regular fa-calendar text-base"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">TANGGAL BERGABUNG</span>
                            <span class="text-sm font-semibold text-slate-700">
                                {{ $anggota->tanggal_daftar ? $anggota->tanggal_daftar->locale('id')->translatedFormat('d F Y') : '-' }}
                            </span>
                        </div>
                    </div>

                    <!-- Expiration Date -->
                    <div class="flex items-start gap-4">
                        <div class="h-10 w-10 rounded-xl bg-rose-50/60 flex items-center justify-center text-rose-600 shrink-0 border border-rose-100/30">
                            <i class="fa-regular fa-id-card text-base"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">MASA BERLAKU KARTU</span>
                            <span class="text-sm font-semibold text-slate-700">
                                {{ $anggota->eKartuAnggota?->masa_berlaku ? $anggota->eKartuAnggota->masa_berlaku->locale('id')->translatedFormat('d F Y') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loan History Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-lg bg-slate-50 flex items-center justify-center text-slate-500">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Riwayat Peminjaman Terakhir</h3>
            </div>
            <a href="#" class="text-sm font-bold text-[#7c6312] hover:underline">Lihat Semua Riwayat</a>
        </div>

        @if ($riwayatPeminjaman->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-450 border-b border-slate-100">
                            <th class="pb-3 text-xs font-bold uppercase tracking-wider">Judul Buku</th>
                            <th class="pb-3 text-xs font-bold uppercase tracking-wider">Tanggal Pinjam</th>
                            <th class="pb-3 text-xs font-bold uppercase tracking-wider">Tanggal Kembali</th>
                            <th class="pb-3 text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="pb-3 text-xs font-bold uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($riwayatPeminjaman as $peminjaman)
                            @php
                                $status = strtolower($peminjaman->status_peminjaman);
                                $badgeClass = 'bg-blue-50 text-blue-700 border-blue-100';
                                $statusText = 'Sedang Dipinjam';

                                if (in_array($status, ['dikembalikan', 'kembali'])) {
                                    $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                                    $statusText = 'Dikembalikan';
                                } elseif (in_array($status, ['terlambat', 'denda'])) {
                                    $badgeClass = 'bg-rose-50 text-rose-700 border-rose-100';
                                    $statusText = 'Terlambat';
                                } elseif (in_array($status, ['pengajuan', 'pending'])) {
                                    $badgeClass = 'bg-amber-50 text-amber-700 border-amber-100';
                                    $statusText = 'Pengajuan';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/20 transition">
                                <td class="py-4">
                                    <div class="font-bold text-slate-800 text-sm">
                                        {{ $peminjaman->detailPeminjaman->first()?->buku?->judul ?? 'Buku' }}
                                    </div>
                                    <div class="text-xs text-slate-400 mt-0.5">
                                        ISBN: {{ $peminjaman->detailPeminjaman->first()?->buku?->isbn ?? '-' }}
                                    </div>
                                </td>
                                <td class="py-4 text-sm font-semibold text-slate-650">
                                    {{ $peminjaman->tanggal_diambil ? $peminjaman->tanggal_diambil->locale('id')->translatedFormat('d M Y') : ($peminjaman->tanggal_pengajuan ? $peminjaman->tanggal_pengajuan->locale('id')->translatedFormat('d M Y') : '-') }}
                                </td>
                                <td class="py-4 text-sm font-semibold text-slate-650">
                                    @if (in_array($status, ['dikembalikan', 'kembali']) && $peminjaman->pengembalian?->created_at)
                                        {{ $peminjaman->pengembalian->created_at->locale('id')->translatedFormat('d M Y') }}
                                    @else
                                        {{ $peminjaman->tanggal_jatuh_tempo ? $peminjaman->tanggal_jatuh_tempo->locale('id')->translatedFormat('d M Y') : '-' }}
                                    @endif
                                </td>
                                <td class="py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <button type="button" class="text-slate-400 hover:text-slate-700 p-2 rounded-lg hover:bg-slate-50 transition">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 px-4 border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3 border border-slate-200/60 shadow-sm">
                    <i class="fa-solid fa-clock-rotate-left text-base"></i>
                </div>
                <p class="text-sm font-bold text-slate-750">Belum Ada Riwayat Peminjaman</p>
                <p class="text-xs text-slate-400 mt-1.5 text-center max-w-[280px] leading-relaxed">Anggota ini belum memiliki transaksi peminjaman buku yang tercatat.</p>
            </div>
        @endif
    </div>
</div>
@endsection
