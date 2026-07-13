@extends('layouts.petugas')

@section('title', 'Detail Pengajuan Peminjaman')

@section('content')
@php use Carbon\Carbon; @endphp
<div class="mx-auto max-w-[1280px]">
    <!-- Breadcrumb -->
    <nav class="flex text-xs font-semibold text-slate-400 mb-6 gap-2 items-center">
        <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-600 transition">Dashboard</a>
        <span>&gt;</span>
        <a href="{{ route('petugas.peminjaman.index') }}" class="hover:text-slate-600 transition">Peminjaman</a>
        <span>&gt;</span>
        <a href="{{ route('petugas.peminjaman.index', ['status' => 'menunggu']) }}" class="hover:text-slate-600 transition">Pengajuan</a>
        <span>&gt;</span>
        <span class="text-slate-600 font-bold">Detail {{ $peminjaman->kode_peminjaman }}</span>
    </nav>

    <!-- Header Block with Back Link and Badge -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('petugas.peminjaman.index') }}" class="h-10 w-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-700 hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Detail Pengajuan Peminjaman</h2>
            @php
                $status = strtolower((string) $peminjaman->status_peminjaman);
                $badge = 'bg-slate-50 text-slate-600';
                $statusLabel = ucwords(str_replace('_', ' ', (string) $peminjaman->status_peminjaman));

                if (in_array($status, ['menunggu', 'pending', 'pengajuan', 'diajukan'])) {
                    $badge = 'bg-amber-50 text-amber-600';
                    $statusLabel = 'Diajukan';
                } elseif ($status === 'siap_diambil') {
                    $badge = 'bg-blue-50 text-blue-600';
                    $statusLabel = 'Siap Diambil';
                } elseif (in_array($status, ['disetujui', 'aktif', 'dipinjam', 'terlambat'])) {
                    $badge = 'bg-emerald-50 text-emerald-600';
                    $statusLabel = $status === 'terlambat' ? 'Terlambat' : 'Aktif';
                } elseif ($status === 'selesai') {
                    $badge = 'bg-slate-100 text-slate-650';
                    $statusLabel = 'Selesai';
                } elseif (in_array($status, ['ditolak', 'batal', 'dibatalkan'])) {
                    $badge = 'bg-rose-50 text-rose-600';
                    $statusLabel = $status === 'ditolak' ? 'Ditolak' : 'Dibatalkan';
                }
            @endphp
            <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold {{ $badge }} ml-2">
                {{ $statusLabel }}
            </span>
        </div>
    </div>

    <!-- Error/Success Alerts -->
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold flex items-center gap-3">
            <i class="fa-solid fa-circle-exclamation text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Content Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        <!-- Left: Member Info -->
        <div class="lg:col-span-5 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-700">
                    <i class="fa-regular fa-user"></i>
                </div>
                <h3 class="text-base font-bold text-slate-850">Informasi Anggota</h3>
            </div>

            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                @if($anggota && $anggota->foto)
                    <img src="{{ asset('storage/' . $anggota->foto) }}" alt="Foto Anggota" class="h-24 w-24 rounded-xl border border-slate-200 object-cover shadow-sm">
                @else
                    <div class="h-24 w-24 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 border border-slate-200">
                        <i class="fa-regular fa-user text-4xl"></i>
                    </div>
                @endif

                <div class="space-y-3.5 text-center sm:text-left flex-1">
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">NAMA LENGKAP</span>
                        <span class="block text-base font-bold text-slate-800 mt-0.5">{{ $anggota->nama_lengkap ?? 'Anggota' }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">NOMOR INDUK</span>
                            <span class="block text-sm font-semibold text-slate-700 mt-0.5">{{ $anggota->no_anggota ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">TIPE ANGGOTA</span>
                            <span class="block text-sm font-semibold text-slate-700 mt-0.5">Anggota</span>
                        </div>
                    </div>

                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">STATUS PEMINJAMAN AKTIF</span>
                        <div class="flex items-center gap-1.5 mt-0.5 text-slate-700">
                            <i class="fa-solid fa-book-bookmark text-slate-400 text-xs"></i>
                            <span class="text-sm font-semibold">{{ $bukuDipinjamCount }} Buku dipinjam saat ini</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Book Info -->
        @php
            $buku = $peminjaman->detailPeminjaman->first()?->buku;
            $eksemplars = $buku ? $buku->eksemplar : collect();
            $tersediaCount = $eksemplars->whereIn('status_eksemplar', ['tersedia', 'Tersedia'])->count();
            $totalCount = $eksemplars->count();
            $firstEksemplar = $eksemplars->first();
        @endphp
        <div class="lg:col-span-7 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-700">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <h3 class="text-base font-bold text-slate-850">Informasi Buku</h3>
            </div>

            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                <x-book-cover :book="$buku" class="h-36 w-24 rounded-lg shadow-sm" icon-class="text-3xl" />

                <div class="space-y-3.5 flex-1 text-center sm:text-left">
                    <div>
                        <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">JUDUL BUKU</span>
                        <h4 class="text-lg font-bold text-slate-850 leading-tight mt-0.5">{{ $buku->judul ?? 'Buku' }}</h4>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">PENULIS</span>
                            <span class="block text-sm font-semibold text-slate-700 mt-0.5">{{ $buku->penulis ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">PENERBIT</span>
                            <span class="block text-sm font-semibold text-slate-700 mt-0.5">{{ $buku->penerbit ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">ISBN</span>
                            <span class="block text-sm font-semibold text-slate-700 mt-0.5">{{ $buku->isbn ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">LOKASI RAK</span>
                            <span class="block text-sm font-semibold text-slate-700 mt-0.5">{{ $firstEksemplar?->lokasi_rak ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">STATUS EKSEMPLAR</span>
                            <span class="block text-sm font-semibold text-slate-700 mt-0.5">{{ $tersediaCount }} Tersedia / {{ $totalCount }} Total</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">KODE EKSEMPLAR SPESIFIK</span>
                            <div class="mt-1">
                                <span class="font-mono text-xs font-semibold px-2 py-1 bg-slate-100 rounded-md text-slate-650">
                                    {{ $firstEksemplar?->kode_eksemplar ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loan Details Panel -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
            <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-700">
                <i class="fa-regular fa-file-lines"></i>
            </div>
            <h3 class="text-base font-bold text-slate-850">Rincian Pengajuan</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-100 flex flex-col gap-1.5">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">TANGGAL PENGAJUAN</span>
                <span class="text-base font-bold text-slate-800">
                    {{ $peminjaman->tanggal_pengajuan ? $peminjaman->tanggal_pengajuan->locale('id')->translatedFormat('d F Y') : ($peminjaman->created_at ? $peminjaman->created_at->locale('id')->translatedFormat('d F Y') : '-') }}
                </span>
                <span class="text-xs text-slate-500">
                    Pukul {{ $peminjaman->tanggal_pengajuan ? $peminjaman->tanggal_pengajuan->format('H:i') : ($peminjaman->created_at ? $peminjaman->created_at->format('H:i') : '-') }} WIB
                </span>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-100 flex flex-col gap-1.5">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">DURASI PERMINTAAN</span>
                <span class="text-base font-bold text-slate-800">
                    {{ $peminjaman->aturanPeminjaman?->lama_pinjam_hari ?? 14 }} Hari
                </span>
                <span class="text-xs text-slate-500">Standard Loan Policy</span>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-100 flex flex-col gap-1.5">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">ESTIMASI KEMBALI</span>
                @php
                    $duration = $peminjaman->aturanPeminjaman?->lama_pinjam_hari ?? 14;
                    $requestDate = $peminjaman->tanggal_pengajuan ?? $peminjaman->created_at ?? now();
                    $estimasiKembali = Carbon::parse($requestDate)->addDays($duration);
                @endphp
                <span class="text-base font-bold text-slate-800">
                    {{ $peminjaman->tanggal_jatuh_tempo ? Carbon::parse($peminjaman->tanggal_jatuh_tempo)->locale('id')->translatedFormat('d F Y') : $estimasiKembali->locale('id')->translatedFormat('d F Y') }}
                </span>
                <span class="text-xs text-slate-500">Sebelum 17:00 WIB</span>
            </div>
        </div>

        @php
            $catatanAnggota = trim((string) $peminjaman->deskripsi_pengajuan);
        @endphp
        <div class="mb-8 rounded-2xl border border-slate-100 bg-slate-50/70 p-5">
            <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">CATATAN ANGGOTA</span>
            <p class="mt-2 whitespace-pre-line text-sm font-medium leading-relaxed text-slate-700">
                {{ $catatanAnggota !== '' ? $catatanAnggota : 'Tidak ada catatan dari anggota.' }}
            </p>
        </div>

        @if(in_array($status, ['menunggu', 'pending', 'pengajuan', 'diajukan']))
            <!-- Actions Section -->
            <div class="flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                <!-- Reject Action -->
                <form action="{{ route('petugas.peminjaman.tolak', $peminjaman->id_peminjaman) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menolak pengajuan ini?')">
                    @csrf
                    <button type="submit" class="px-5 py-3 border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-sm rounded-xl transition">
                        <i class="fa-solid fa-xmark mr-2"></i> Tolak Pengajuan
                    </button>
                </form>

                <!-- Approve Schedule -->
                <a href="{{ route('petugas.peminjaman.approve-form', $peminjaman->id_peminjaman) }}" class="px-5 py-3 bg-[#0e1f30] text-white hover:bg-[#122b42] font-bold text-sm rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i> Setujui & Atur Jadwal
                </a>
            </div>
        @endif

        @if($status === 'siap_diambil')
            <!-- Actions Section for Ready to Pick Up -->
            <div class="flex flex-col items-stretch justify-end gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center">
                <!-- Cancel Pickup Action -->
                <form action="{{ route('petugas.peminjaman.batalkan-pengambilan', $peminjaman->id_peminjaman) }}" method="POST" class="inline" onsubmit="return confirm('Batalkan pengambilan buku ini? Eksemplar akan dikembalikan menjadi tersedia.')">
                    @csrf
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border border-rose-200 px-5 py-3 text-sm font-bold text-rose-600 transition hover:bg-rose-50 sm:w-auto">
                        <i class="fa-solid fa-ban"></i> Batalkan Pengambilan
                    </button>
                </form>

                <!-- Mark as Picked Up Action -->
                <form action="{{ route('petugas.peminjaman.ambil', $peminjaman->id_peminjaman) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menandai buku ini telah diambil oleh anggota?')">
                    @csrf
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700 sm:w-auto">
                        <i class="fa-solid fa-hand-holding-hand"></i> Tandai Diambil (Serahkan Buku)
                    </button>
                </form>
            </div>
        @endif

    </div>
</div>
@endsection
