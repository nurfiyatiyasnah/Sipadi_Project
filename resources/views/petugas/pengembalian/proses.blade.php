@extends('layouts.petugas')

@section('title', 'Form Pengembalian Buku')

@section('content')
<div class="mx-auto max-w-[960px]">
    <!-- Breadcrumb -->
    <nav class="flex text-xs font-semibold text-slate-400 mb-6 gap-2 items-center">
        <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-600 transition">Dashboard</a>
        <span>&gt;</span>
        <a href="{{ route('petugas.pengembalian.index') }}" class="hover:text-slate-600 transition">Pengembalian</a>
        <span>&gt;</span>
        <span class="text-slate-600 font-bold">Pengembalian Buku</span>
    </nav>

    <!-- Header Block -->
    <div class="mb-8">
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Pengembalian Buku</h2>
        <p class="text-sm text-slate-500 mt-1">Formulir pengembalian buku dan evaluasi kondisi.</p>
    </div>

    <!-- Form Section -->
    <form action="{{ route('petugas.pengembalian.proses-sanksi', $peminjaman->id_peminjaman) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 space-y-8 mb-8">
            <!-- Row 1: Ringkasan Anggota & Informasi Peminjaman -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pb-6 border-b border-slate-100">
                <!-- Ringkasan Anggota -->
                <div>
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-3">Ringkasan Anggota</span>
                    @php
                        $anggota = $peminjaman->anggota;
                        $initials = collect(explode(' ', $anggota?->nama_lengkap ?? 'A'))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                    @endphp
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-sm font-bold text-slate-600">
                            {{ strtoupper($initials) }}
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-850">{{ $anggota?->nama_lengkap }}</h4>
                            <p class="text-xs text-slate-400 mt-0.5">ID: {{ $anggota?->no_anggota }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 mt-1 bg-blue-50 text-blue-600 rounded-md text-[10px] font-bold">
                                Anggota
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Peminjaman -->
                <div class="space-y-2">
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Informasi Peminjaman</span>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="text-slate-400 font-semibold">ID Transaksi</div>
                        <div class="text-slate-750 font-bold text-right">{{ $peminjaman->kode_peminjaman }}</div>
                        
                        <div class="text-slate-400 font-semibold">Tanggal Pinjam</div>
                        <div class="text-slate-750 font-bold text-right">
                            {{ $peminjaman->tanggal_diambil ? $peminjaman->tanggal_diambil->locale('id')->translatedFormat('d M Y') : '-' }}
                        </div>
                        
                        <div class="text-slate-400 font-semibold">Tenggat Waktu</div>
                        <div class="text-slate-750 font-bold text-right">
                            {{ $peminjaman->tanggal_jatuh_tempo ? $peminjaman->tanggal_jatuh_tempo->locale('id')->translatedFormat('d M Y') : '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 2: Ringkasan Buku -->
            @php
                $firstDetail = $peminjaman->detailPeminjaman->first();
                $buku = $firstDetail?->buku;
                $firstEksemplar = $buku?->eksemplar?->first();
            @endphp
            <div class="pb-6 border-b border-slate-100">
                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-3">Ringkasan Buku</span>
                <div class="flex items-center gap-4 bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                    <x-book-cover :book="$buku" class="h-16 w-12 rounded" icon-class="text-base" />
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">{{ $buku?->judul }}</h4>
                        <p class="text-xs text-slate-450 mt-0.5">{{ $buku?->penulis }} • Barcode: {{ $firstEksemplar?->kode_eksemplar ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Row 3: Formulir Input -->
            <div class="space-y-6">
                <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-400">Form Pengembalian</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tanggal Pengembalian -->
                    <div>
                        <label class="block text-xs font-bold text-slate-650 uppercase tracking-wider mb-2">Tanggal Pengembalian <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="fa-regular fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-slate-450"></i>
                            <input type="date" name="tanggal_pengembalian" value="{{ date('Y-m-d') }}" required class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-750 focus:outline-none focus:border-slate-350 bg-white">
                        </div>
                    </div>

                    <!-- Keadaan Buku -->
                    <div>
                        <label class="block text-xs font-bold text-slate-650 uppercase tracking-wider mb-2">Keadaan Buku <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-shield-halved absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-450"></i>
                            <select name="keadaan_buku" required class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-750 focus:outline-none focus:border-slate-350 bg-white">
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                                <option value="Hilang">Hilang</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Catatan Kondisi Buku -->
                <div>
                    <label class="block text-xs font-bold text-slate-650 uppercase tracking-wider mb-2">Catatan Kondisi Buku</label>
                    <textarea name="catatan_kondisi" rows="3" placeholder="Tambahkan catatan jika ada kerusakan pada buku..." class="w-full p-4 border border-slate-200 rounded-xl text-sm placeholder:text-slate-400 focus:outline-none focus:border-slate-350 transition"></textarea>
                </div>

                <!-- Upload Foto Kondisi Buku -->
                <div>
                    <label class="block text-xs font-bold text-slate-650 uppercase tracking-wider mb-2">Upload Foto Kondisi Buku (Opsional)</label>
                    <div class="border-2 border-dashed border-slate-200 hover:border-slate-300 transition rounded-xl p-8 text-center bg-slate-50/50 cursor-pointer relative" onclick="document.getElementById('foto_kondisi').click()">
                        <input type="file" id="foto_kondisi" name="foto_kondisi" accept="image/*" class="hidden" onchange="updateFileLabel(this)">
                        <div class="space-y-2">
                            <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-400"></i>
                            <p class="text-xs text-slate-500 font-bold" id="file_label">Upload file atau drag and drop</p>
                            <p class="text-[10px] text-slate-400">PNG, JPG, GIF up to 5MB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('petugas.pengembalian.show', $peminjaman->id_peminjaman) }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-sm rounded-xl transition shadow-sm">
                    <i class="fa-solid fa-chevron-left mr-1.5"></i> Kembali
                </a>
                <a href="{{ route('petugas.pengembalian.index') }}" class="text-slate-500 hover:text-slate-700 text-sm font-bold ml-2">
                    Batal
                </a>
            </div>
            
            <div class="flex items-center gap-3">
                <button type="submit" class="px-5 py-2.5 bg-[#f0c243] hover:bg-[#d8ae3c] text-slate-850 font-bold text-sm rounded-xl transition shadow-sm">
                    Lanjut ke Detail Pengembalian & Sanksi <i class="fa-solid fa-arrow-right ml-1.5"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function updateFileLabel(input) {
        const label = document.getElementById('file_label');
        if (input.files && input.files.length > 0) {
            label.textContent = 'Terpilih: ' + input.files[0].name;
            label.className = 'text-xs text-emerald-600 font-bold';
        } else {
            label.textContent = 'Upload file atau drag and drop';
            label.className = 'text-xs text-slate-500 font-bold';
        }
    }
</script>
@endsection
