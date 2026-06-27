<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Lacak Status Aduan - SIPADI Bukittinggi</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f5e9] font-sans text-[#061b3a] antialiased">

    <!-- Header / Navbar -->
    @include('layouts.public_navbar')

    <!-- Main Content -->
    <main class="py-12 lg:py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-12">
            
            <!-- Page Title Section -->
            <div class="text-center mb-12">
                <h1 class="font-serif text-4xl lg:text-5xl font-bold text-[#04241e]">Status Aduan Pengunjung</h1>
                <p class="mt-4 text-slate-500 text-sm max-w-xl mx-auto leading-relaxed">
                    Lacak perkembangan laporan, saran, atau keluhan yang telah Anda sampaikan kepada Dinas Perpustakaan dan Kearsipan Kota Bukittinggi.
                </p>
            </div>

            <!-- Success Alert (Redirect from store) -->
            @if (session('success'))
                <div class="mb-8 max-w-5xl mx-auto rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-start gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="font-bold">Berhasil!</p>
                        <p class="mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] items-start max-w-5xl mx-auto">
                
                <!-- Left Column: Search & Details -->
                <div class="space-y-8">
                    
                    <!-- Search Card -->
                    <div class="bg-white border border-slate-200/50 rounded-[1.8rem] p-6 lg:p-8 shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-500 flex-shrink-0">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <h3 class="text-lg font-bold text-[#04241e]">Cek Nomor Tiket</h3>
                        </div>

                        <form action="{{ route('aduan.track') }}" method="GET" class="mt-6 space-y-4">
                            <div>
                                <label for="ticket" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nomor Tiket / Referensi</label>
                                <input type="text" id="ticket" name="ticket" 
                                    class="w-full rounded-xl bg-slate-50 border-slate-200/80 focus:border-[#04241e] focus:ring-[#04241e] py-3.5 px-4 text-slate-800 font-semibold transition shadow-sm text-sm" 
                                    value="{{ $ticketCode }}" placeholder="Contoh: AD-2026-06-001" required>
                            </div>

                            <button type="submit" class="w-full rounded-xl bg-[#04241e] hover:bg-[#06342c] py-3.5 text-sm font-bold text-white shadow-md hover:shadow-lg transition duration-200 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Lacak Status
                            </button>
                        </form>
                    </div>

                    <!-- Detail Card (Only if aduan is loaded) -->
                    @if ($aduan)
                        <div class="bg-white border border-slate-200/50 rounded-[1.8rem] p-6 lg:p-8 shadow-sm space-y-6">
                            <h3 class="text-lg font-bold text-[#04241e] border-b border-slate-100 pb-3">Detail Aduan</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Nomor Tiket</p>
                                    <p class="text-base font-bold text-[#04241e] font-mono mt-1">{{ $aduan->kode_aduan }}</p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Tanggal Laporan</p>
                                    <p class="text-sm text-slate-600 mt-1 flex items-center gap-1.5">
                                        <i class="fa-regular fa-calendar text-xs text-slate-400"></i>
                                        {{ $aduan->created_at->locale('id')->translatedFormat('d F Y, H:i') }} WIB
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Kategori</p>
                                    <div class="mt-1">
                                        <span class="inline-block bg-emerald-50 text-emerald-700 border border-emerald-200/50 px-3 py-0.5 text-xs font-semibold rounded-full">
                                            {{ $aduan->kategori_aduan }}
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Isi Laporan</p>
                                    <div class="mt-2 bg-blue-50 border border-blue-100/50 p-4 rounded-2xl text-slate-700 text-sm leading-relaxed whitespace-pre-line">
                                        {{ $aduan->isi_aduan }}
                                    </div>
                                </div>

                                @if ($aduan->lampiran)
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Lampiran</p>
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $aduan->lampiran) }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-emerald-600 hover:text-emerald-700 transition">
                                                <i class="fa-solid fa-paperclip text-sm"></i>
                                                Lihat Lampiran Pendukung
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @elseif($ticketCode)
                        <!-- Searched but not found -->
                        <div class="bg-white border border-slate-200/50 rounded-[1.8rem] p-6 lg:p-8 text-center shadow-sm">
                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500 mx-auto text-xl mb-4">
                                <i class="fa-solid fa-circle-exclamation"></i>
                            </span>
                            <p class="text-sm font-bold text-slate-700">Nomor tiket tidak ditemukan.</p>
                            <p class="text-xs text-slate-400 mt-1">Silakan periksa kembali kode tiket yang Anda masukkan.</p>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Status & Feedbacks -->
                <div class="space-y-8">
                    @if ($aduan)
                        <!-- Status Proses (Timeline) -->
                        <div class="bg-white border border-slate-200/50 rounded-[1.8rem] p-6 lg:p-8 shadow-sm">
                            <h3 class="text-lg font-bold text-[#04241e] border-b border-slate-100 pb-3 mb-6">Status Proses</h3>
                            
                            <!-- Timeline List -->
                            <div class="relative pl-6 border-l border-slate-100 space-y-8">
                                
                                <!-- Step 1: Terkirim -->
                                <div class="relative">
                                    <!-- Node Icon -->
                                    <span class="absolute -left-[35px] top-0 flex h-[18px] w-[18px] items-center justify-center rounded-full bg-emerald-50 text-emerald-600 ring-4 ring-white">
                                        <i class="fa-solid fa-circle-check text-base"></i>
                                    </span>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800 leading-none">Terkirim</h4>
                                        <p class="text-xs text-slate-500 mt-1">Laporan berhasil diterima oleh sistem SIPADI.</p>
                                        <p class="text-[10px] font-bold text-slate-400 mt-1.5 uppercase tracking-wider">
                                            {{ $aduan->created_at->locale('id')->translatedFormat('d M Y, H:i') }} WIB
                                        </p>
                                    </div>
                                </div>

                                <!-- Step 2: Diproses -->
                                @php
                                    $isDiproses = in_array($aduan->status_aduan, ['diproses', 'selesai']);
                                    $diprosesTime = $isDiproses ? ($aduan->tanggapan->first()?->created_at ?? $aduan->updated_at) : null;
                                @endphp
                                <div class="relative">
                                    <!-- Node Icon -->
                                    @if ($isDiproses)
                                        <span class="absolute -left-[35px] top-0 flex h-[18px] w-[18px] items-center justify-center rounded-full bg-amber-50 text-amber-500 ring-4 ring-white">
                                            <i class="fa-solid fa-arrows-rotate text-sm"></i>
                                        </span>
                                    @else
                                        <span class="absolute -left-[35px] top-0 flex h-[18px] w-[18px] items-center justify-center rounded-full bg-slate-100 text-slate-300 ring-4 ring-white">
                                            <i class="fa-regular fa-circle text-[10px]"></i>
                                        </span>
                                    @endif
                                    <div>
                                        <h4 class="text-sm font-bold {{ $isDiproses ? 'text-slate-800' : 'text-slate-400' }} leading-none">Diproses</h4>
                                        <p class="text-xs text-slate-500 mt-1">Laporan sedang ditindaklanjuti oleh petugas terkait.</p>
                                        @if ($diprosesTime)
                                            <p class="text-[10px] font-bold text-slate-400 mt-1.5 uppercase tracking-wider">
                                                {{ $diprosesTime->locale('id')->translatedFormat('d M Y, H:i') }} WIB
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Step 3: Selesai -->
                                @php
                                    $isSelesai = $aduan->status_aduan === 'selesai';
                                    $selesaiTime = $isSelesai ? $aduan->updated_at : null;
                                @endphp
                                <div class="relative">
                                    <!-- Node Icon -->
                                    @if ($isSelesai)
                                        <span class="absolute -left-[35px] top-0 flex h-[18px] w-[18px] items-center justify-center rounded-full bg-emerald-50 text-emerald-600 ring-4 ring-white">
                                            <i class="fa-solid fa-circle-check text-base"></i>
                                        </span>
                                    @else
                                        <span class="absolute -left-[35px] top-0 flex h-[18px] w-[18px] items-center justify-center rounded-full bg-slate-100 text-slate-300 ring-4 ring-white">
                                            <i class="fa-solid fa-circle-check text-base"></i>
                                        </span>
                                    @endif
                                    <div>
                                        <h4 class="text-sm font-bold {{ $isSelesai ? 'text-slate-800' : 'text-slate-400' }} leading-none">Selesai</h4>
                                        <p class="text-xs text-slate-500 mt-1">Tindakan telah selesai dilakukan dan aduan ditutup.</p>
                                        @if ($selesaiTime)
                                            <p class="text-[10px] font-bold text-slate-400 mt-1.5 uppercase tracking-wider">
                                                {{ $selesaiTime->locale('id')->translatedFormat('d M Y, H:i') }} WIB
                                            </p>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Tanggapan Petugas -->
                        @if ($aduan->tanggapan->isNotEmpty())
                            <div class="bg-white border border-slate-200/50 rounded-[1.8rem] p-6 lg:p-8 shadow-sm">
                                <h3 class="text-lg font-bold text-[#04241e] border-b border-slate-100 pb-3 mb-6 flex items-center gap-2">
                                    <i class="fa-regular fa-comment-dots"></i>
                                    Tanggapan Petugas
                                </h3>

                                <div class="space-y-6">
                                    @foreach ($aduan->tanggapan as $respon)
                                        <div class="relative bg-slate-50/50 border border-slate-100 rounded-2xl p-6 overflow-hidden">
                                            <!-- Quote Decoration -->
                                            <div class="absolute right-4 top-4 opacity-5 pointer-events-none">
                                                <i class="fa-solid fa-quote-right text-6xl text-slate-800"></i>
                                            </div>

                                            <div class="flex gap-4 items-start relative z-10">
                                                <!-- Avatar initials -->
                                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 font-bold flex-shrink-0 border border-emerald-100">
                                                    {{ mb_substr($respon->petugas->nama_petugas ?? 'AD', 0, 2) }}
                                                </span>
                                                
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <h4 class="font-bold text-slate-800 text-sm leading-snug">
                                                            {{ $respon->petugas->nama_petugas ?? 'Petugas SIPADI' }}
                                                        </h4>
                                                        <span class="bg-blue-50 text-blue-700 font-bold px-2 py-0.5 rounded text-[10px] uppercase tracking-wider">
                                                            Staff
                                                        </span>
                                                    </div>
                                                    
                                                    <p class="text-[10px] font-semibold text-slate-400 mt-1 uppercase tracking-wider">
                                                        {{ $respon->created_at->locale('id')->translatedFormat('d M Y, H:i') }} WIB
                                                    </p>

                                                    <p class="text-sm text-slate-600 mt-4 leading-relaxed whitespace-pre-line">
                                                        {{ $respon->isi_tanggapan }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    @else
                        <!-- Public empty / initial state -->
                        <div class="bg-white border border-slate-200/50 rounded-[1.8rem] p-12 text-center shadow-sm flex flex-col items-center justify-center h-full min-h-[300px]">
                            <span class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-300 text-3xl mb-4">
                                <i class="fa-solid fa-ticket-simple"></i>
                            </span>
                            <h4 class="text-base font-bold text-slate-700">Lacak Status Pengaduan</h4>
                            <p class="text-sm text-slate-400 mt-2 max-w-sm leading-relaxed mx-auto">
                                Masukkan nomor tiket valid di samping untuk melacak status aduan Anda secara real-time.
                            </p>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
            <div class="flex flex-col lg:flex-row justify-between items-start gap-8">
                <!-- Branding -->
                <div>
                    <a href="{{ route('landing') }}" class="flex items-center gap-3 hover:opacity-90 transition">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#04241e] text-[#ffdc7c]">
                            <i class="fa-solid fa-building-columns text-sm"></i>
                        </span>
                        <span class="font-serif font-bold text-lg text-[#04241e] tracking-tight">SIPADI</span>
                    </a>
                    <p class="mt-4 text-sm text-slate-500 max-w-sm leading-relaxed">
                        Sistem Informasi Perpustakaan dan Arsip Digital Terintegrasi Kota Bukittinggi. Menghubungkan masyarakat dengan sumber pengetahuan tanpa batas.
                    </p>
                </div>

                <!-- Footer Navigation -->
                <div class="flex flex-wrap gap-x-8 gap-y-4 text-sm font-semibold text-slate-600">
                    <a href="#" class="hover:text-[#04241e] transition">Tentang Kami</a>
                    <a href="#" class="hover:text-[#04241e] transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-[#04241e] transition">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-[#04241e] transition">Peta Situs</a>
                    <a href="#" class="hover:text-[#04241e] transition">Hubungi Kami</a>
                </div>
            </div>

            <!-- Copyright Area -->
            <div class="border-t border-slate-100 mt-8 pt-8 flex flex-col sm:flex-row justify-between text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} Dinas Perpustakaan dan Kearsipan Kota Bukittinggi. Seluruh Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

</body>
</html>
