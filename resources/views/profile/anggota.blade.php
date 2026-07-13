@extends('layouts.public')

@section('title', 'Profil Anggota')

@section('content')
<div class="max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-12 py-8 space-y-8" x-data="{ showQr: false }">
    
    <!-- Page Header -->
    <div class="border-b border-slate-200/60 pb-5">
        <h1 class="break-words font-serif text-3xl font-bold text-[#04241e]">Profil Anggota</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola informasi pribadi dan pengaturan akun Anda.</p>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-start gap-3">
            <i class="fa-solid fa-circle-check text-emerald-600 text-lg shrink-0 mt-0.5"></i>
            <span class="min-w-0 break-words">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Two Column Grid -->
    <div class="grid min-w-0 grid-cols-1 gap-8 lg:grid-cols-[0.9fr_1.6fr] items-start">
        
        <!-- Left Column: Photo & Membership Card -->
        <div class="min-w-0 space-y-6">
            
            <!-- Profile Info Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm text-center relative overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-slate-50 rounded-full opacity-50"></div>
                
                <!-- Avatar -->
                <div class="relative w-36 h-36 mx-auto rounded-full border-4 border-[#ffdc7c]/40 overflow-hidden bg-slate-50 flex items-center justify-center shadow-inner">
                    @if ($anggota->foto)
                        <img src="{{ asset('storage/' . $anggota->foto) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <span class="text-5xl font-serif font-bold text-[#04241e]">{{ mb_substr($anggota->nama_lengkap, 0, 1) }}</span>
                    @endif
                </div>

                <!-- Name & Details -->
                <h2 class="break-words text-xl font-bold text-[#061b3a] mt-5 leading-snug">{{ $anggota->nama_lengkap }}</h2>
                <p class="break-words text-xs text-slate-400 mt-1 font-semibold">{{ $anggota->kalangan ?? 'Anggota Perpustakaan' }}</p>

                <!-- Status Badge -->
                <div class="flex justify-center mt-3">
                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold border border-emerald-100/50">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Akun Aktif
                    </span>
                </div>

                <hr class="my-6 border-slate-100">

                <!-- Registered / Expiry dates -->
                <div class="grid grid-cols-1 gap-4 text-left text-xs sm:grid-cols-2">
                    <div>
                        <p class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Terdaftar Sejak</p>
                        <p class="break-words text-slate-700 font-bold mt-1">
                            {{ $anggota->tanggal_daftar ? $anggota->tanggal_daftar->locale('id')->translatedFormat('d M Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Masa Aktif</p>
                        <p class="break-words text-slate-700 font-bold mt-1">
                            {{ $anggota->tanggal_daftar ? $anggota->tanggal_daftar->addYears(3)->locale('id')->translatedFormat('d M Y') : '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Nomad Card (Digital Member Card Mockup) -->
            <div class="bg-[#04241e] text-white p-6 sm:p-8 rounded-3xl relative overflow-hidden shadow-xl shadow-[#04241e]/10 flex flex-col justify-between min-h-[200px]">
                <!-- Decorative background elements -->
                <div class="absolute right-0 bottom-0 top-0 w-24 bg-gradient-to-l from-white/5 to-transparent flex items-center justify-center opacity-40">
                    <i class="fa-solid fa-qrcode text-8xl text-white/10"></i>
                </div>
                
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-slate-300 font-bold text-[10px] uppercase tracking-widest">Nomor Anggota</p>
                        <p class="break-all text-2xl font-mono font-bold tracking-wide mt-1.5">{{ $anggota->no_anggota }}</p>
                    </div>
                    <i class="fa-regular fa-id-card text-2xl text-[#ffdc7c]"></i>
                </div>

                <div class="relative z-10">
                    <button @click="showQr = true" class="w-full bg-white/10 hover:bg-white/15 active:bg-white/20 text-white font-bold rounded-xl py-3 text-xs tracking-wider transition duration-150 border border-white/20 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-qrcode text-sm"></i>
                        TAMPILKAN QR
                    </button>
                </div>
            </div>

        </div>

        <!-- Right Column: Personal Data & Stats -->
        <div class="min-w-0 space-y-6">
            
            <!-- Informasi Pribadi Card -->
            <div class="min-w-0 bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <h3 class="text-base font-bold text-[#04241e] border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fa-regular fa-user"></i> Informasi Pribadi
                </h3>
                
                <div class="grid gap-6 md:grid-cols-2 text-sm">
                    <div>
                        <p class="text-slate-400 font-semibold text-xs">Nama Lengkap</p>
                        <p class="break-words text-slate-800 font-bold mt-1">{{ $anggota->nama_lengkap }}</p>
                    </div>

                    <div>
                        <p class="text-slate-400 font-semibold text-xs">Nomor Induk Kependudukan (NIK)</p>
                        <p class="break-all text-slate-800 font-bold mt-1 font-mono">{{ $anggota->nik }}</p>
                    </div>

                    <div>
                        <p class="text-slate-400 font-semibold text-xs">Tempat, Tanggal Lahir</p>
                        <p class="break-words text-slate-800 font-bold mt-1">
                            Bukittinggi, {{ $anggota->tanggal_lahir ? $anggota->tanggal_lahir->locale('id')->translatedFormat('d F Y') : '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-slate-400 font-semibold text-xs">Jenis Kelamin</p>
                        <p class="break-words text-slate-800 font-bold mt-1">{{ $anggota->jenis_kelamin ?? 'Perempuan' }}</p>
                    </div>
                </div>
            </div>

            <!-- Kontak & Alamat Card -->
            <div class="min-w-0 bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <h3 class="text-base font-bold text-[#04241e] border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fa-regular fa-address-book"></i> Kontak & Alamat
                </h3>
                
                <div class="grid gap-6 md:grid-cols-2 text-sm">
                    <div>
                        <p class="text-slate-400 font-semibold text-xs">Email</p>
                        <p class="truncate text-slate-800 font-bold mt-1" title="{{ $user->email }}">{{ $user->email }}</p>
                    </div>

                    <div>
                        <p class="text-slate-400 font-semibold text-xs">Nomor Telepon / WhatsApp</p>
                        <p class="break-all text-slate-800 font-bold mt-1 font-mono">{{ $anggota->no_telepon ?? '-' }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-slate-400 font-semibold text-xs">Alamat Sesuai KTP</p>
                        <p class="break-words text-slate-800 font-bold mt-1 leading-relaxed">{{ $anggota->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Stats Boxes Cards Row -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Stat 1: Buku Dipinjam -->
                <div class="min-w-0 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex items-center gap-4 transition hover:shadow-md">
                    <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-book-open text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-2xl font-extrabold text-[#061b3a]">{{ $bukuDipinjamCount }}</h4>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Buku Dipinjam</p>
                    </div>
                </div>

                <!-- Stat 2: Keterlambatan -->
                <div class="min-w-0 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex items-center gap-4 transition hover:shadow-md">
                    <div class="h-12 w-12 rounded-xl {{ $keterlambatanCount > 0 ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600' }} flex items-center justify-center">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-2xl font-extrabold text-[#061b3a]">{{ $keterlambatanCount }}</h4>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Keterlambatan</p>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- QR Modal Backed by AlpineJS -->
    <div x-show="showQr" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" 
         x-transition
         style="display: none;">
        
        <div class="bg-white w-full max-w-sm rounded-3xl p-8 shadow-2xl relative" @click.outside="showQr = false">
            <!-- Close button -->
            <button @click="showQr = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Card Header -->
            <div class="text-center space-y-2 mb-6">
                <h4 class="font-bold text-slate-800 text-lg">Barcode Anggota</h4>
                <p class="text-xs text-slate-400">Gunakan QR/Barcode ini untuk mempermudah transaksi peminjaman di perpustakaan.</p>
            </div>

            <!-- QR code Mockup design -->
            <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-6 flex flex-col items-center justify-center space-y-4">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100">
                    <!-- Dynamic QRCode placeholder using Google Chart API or standard QR symbol -->
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($anggota->no_anggota) }}" 
                         alt="QR Code" class="w-36 h-36">
                </div>
                <div class="text-center">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID ANGGOTA</span>
                    <p class="break-all font-mono text-sm font-bold text-slate-700 mt-0.5">{{ $anggota->no_anggota }}</p>
                </div>
            </div>

            <!-- Footer Close -->
            <div class="mt-6">
                <button @click="showQr = false" class="w-full bg-[#04241e] hover:bg-[#0a4d3f] text-white font-bold rounded-xl py-3 text-xs tracking-wider transition">
                    TUTUP
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
