@extends('layouts.public')

@section('title', 'E-Kartu Anggota - SIPADI Bukittinggi')

@section('content')
<style>
    @media print {
        @page {
            size: landscape;
            margin: 10mm;
        }
        body {
            background: white !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        header, footer, .no-print {
            display: none !important;
        }
        .print-card-wrapper {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            min-height: 100vh !important;
            background: transparent !important;
        }
        .e-card-element {
            box-shadow: none !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #061b3a !important;
        }
    }
</style>

<div class="mx-auto max-w-7xl px-6 lg:px-12 py-16">
    <!-- Page Title Section -->
    <div class="text-center mb-12 no-print">
        <h1 class="font-serif text-4xl lg:text-5xl font-bold text-[#04241e]">E-Kartu Anggota</h1>
        <p class="mt-4 text-slate-500 text-sm max-w-xl mx-auto leading-relaxed">
            Kartu identitas digital Anda untuk mengakses seluruh layanan dan fasilitas Perpustakaan Kota Bukittinggi.
        </p>
    </div>

    <!-- Card Container Box -->
    <div class="bg-white border border-slate-200/80 rounded-[2.5rem] p-8 lg:p-12 shadow-md shadow-[#04241e]/5">
        <div class="grid grid-cols-1 min-[1200px]:grid-cols-[auto_1fr] gap-12 xl:gap-16 items-start">
            
            <!-- Left: Card Display Wrapper -->
            <div class="flex items-center justify-center min-[1200px]:justify-start print-card-wrapper w-full">
                <div class="w-full max-w-[580px] e-card-element overflow-hidden rounded-[2rem] bg-[#061b3a] p-8 text-white shadow-2xl shadow-[#061b3a]/20">
                    <div class="flex flex-col justify-between gap-8 sm:flex-row">
                        <div class="flex-1">
                            <p class="text-sm font-bold uppercase tracking-[0.24em] text-[#ffdc7c]">SIPADI Bukittinggi</p>
                            <h3 class="mt-3 text-3xl font-bold">Kartu Anggota Digital</h3>

                            <dl class="mt-8 grid gap-5 rounded-3xl bg-white/10 p-6 text-sm">
                                <div>
                                    <dt class="text-slate-300">Nama Anggota</dt>
                                    <dd class="mt-1 text-2xl font-bold">{{ $anggota->nama_lengkap }}</dd>
                                </div>
                                <div>
                                    <dt class="text-slate-300">Nomor Kartu / NIK</dt>
                                    <dd class="mt-1 font-mono text-xl font-bold">{{ $eKartu->no_anggota }}</dd>
                                </div>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <dt class="text-slate-300">Kalangan</dt>
                                        <dd class="mt-1 font-semibold">{{ $eKartu->kalangan }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-slate-300">Berlaku Sampai</dt>
                                        <dd class="mt-1 font-semibold">{{ $eKartu->masa_berlaku?->translatedFormat('d F Y') }}</dd>
                                    </div>
                                </div>
                            </dl>
                        </div>

                        <div class="flex min-w-56 flex-col justify-between rounded-3xl bg-white p-5 text-[#061b3a]">
                            <div class="flex justify-center">
                                @if ($anggota->foto)
                                    <img src="{{ asset('storage/'.$anggota->foto) }}" alt="Foto {{ $anggota->nama_lengkap }}" class="h-28 w-28 rounded-3xl object-cover ring-4 ring-[#ffdc7c]/70">
                                @else
                                    <div class="flex h-28 w-28 items-center justify-center rounded-3xl bg-[#f6f5e9] text-4xl font-bold ring-4 ring-[#ffdc7c]/70">
                                        {{ mb_substr($anggota->nama_lengkap, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6 rounded-2xl bg-[#f6f5e9] p-4 text-center">
                                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Kode Kartu</p>
                                <p class="mt-2 break-all font-mono text-xs">{{ $eKartu->barcode }}</p>
                            </div>
                            <div class="mt-4 rounded-full bg-emerald-50 px-4 py-2 text-center text-sm font-bold text-emerald-700">
                                Aktif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Info Panel & Action Buttons -->
            <div class="flex flex-col gap-8 no-print h-full justify-between w-full max-w-xl mx-auto min-[1200px]:mx-0">
                
                <!-- Status Keanggotaan Card -->
                <div class="bg-slate-50 border border-slate-200/60 rounded-3xl p-8 shadow-sm flex-1">
                    <div class="flex items-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                            <i class="fa-solid fa-circle-check text-2xl"></i>
                        </span>
                        <h3 class="text-xl font-bold text-[#04241e]">Status Keanggotaan</h3>
                    </div>

                    <div class="flex items-center gap-2 mt-5">
                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200/50 px-4 py-1 text-xs font-bold rounded-full">
                            Aktif
                        </span>
                        <span class="text-slate-400 text-sm font-semibold ml-2">
                            Reguler
                        </span>
                    </div>

                    <hr class="border-slate-100/80 my-6">

                    <p class="text-slate-500 text-sm leading-relaxed">
                        Tunjukkan E-Kartu ini atau pindai QR Code pada mesin kiosk di perpustakaan untuk meminjam buku dan menggunakan fasilitas.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Download PDF -->
                    <a href="{{ route('anggota.e-kartu.download') }}" class="flex items-center justify-center gap-2 rounded-3xl bg-[#04241e] hover:bg-[#06342c] px-6 py-4 text-base font-bold text-white shadow-md hover:shadow-lg transition duration-300">
                        <i class="fa-solid fa-download"></i>
                        Unduh PDF
                    </a>

                    <!-- Print Card -->
                    <button onclick="window.print()" class="flex items-center justify-center gap-2 rounded-3xl border border-[#04241e] hover:bg-slate-50 bg-white px-6 py-4 text-base font-bold text-[#04241e] shadow-sm hover:shadow-md transition duration-300">
                        <i class="fa-solid fa-print"></i>
                        Cetak Kartu
                    </button>
                </div>

                <!-- Problem/Help Link -->
                <div class="text-center mt-2">
                    <a href="{{ route('landing') }}#kontak" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-slate-600 transition">
                        <i class="fa-regular fa-circle-question text-sm"></i>
                        Masalah dengan kartu Anda?
                    </a>
                </div>
                
            </div>

        </div>
    </div>
</div>
@endsection
