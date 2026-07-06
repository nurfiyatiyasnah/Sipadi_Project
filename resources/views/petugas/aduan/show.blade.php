@extends('layouts.petugas')

@section('title', 'Detail Aduan #' . $aduan->kode_aduan)

@section('content')
<div class="space-y-6">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 no-print">
        <a href="{{ route('petugas.aduan.index') }}" class="hover:text-slate-600 transition">Aduan</a>
        <i class="fa-solid fa-chevron-right text-[8px]"></i>
        <span class="text-slate-600">Detail Aduan #{{ $aduan->kode_aduan }}</span>
    </div>

    <!-- Page Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl font-bold text-slate-800">Detail Aduan</h1>
            <p class="text-sm text-slate-500 mt-1">Lihat laporan aduan dan berikan tanggapan untuk penyelesaian.</p>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center gap-3 no-print">
            <!-- Archive form -->
            <form action="{{ route('petugas.aduan.toggle-arsip', $aduan) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 px-5 py-2.5 text-sm font-bold text-slate-600 shadow-sm transition duration-200 flex items-center gap-2">
                    @if ($aduan->arsip)
                        <i class="fa-solid fa-box-open"></i>
                        Kembalikan dari Arsip
                    @else
                        <i class="fa-solid fa-box-archive"></i>
                        Arsipkan
                    @endif
                </button>
            </form>

            @if (!$aduan->arsip && $aduan->status_aduan !== 'selesai')
                <!-- Reply button -->
                <a href="{{ route('petugas.aduan.tanggapi', $aduan) }}" class="rounded-xl bg-[#0e1f30] hover:bg-[#1b2e46] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-reply"></i>
                    Tanggapi Aduan
                </a>
            @endif
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Content Grid -->
    <div class="grid gap-8 lg:grid-cols-[1.6fr_0.9fr] items-start">
        
        <!-- Left: Complaint and response logs -->
        <div class="space-y-6">
            
            <!-- Complaint Details Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <h2 class="text-2xl font-bold text-slate-800 leading-snug">
                        {{ $aduan->subjek }}
                    </h2>
                    
                    <div>
                        @if ($aduan->arsip)
                            <span class="inline-block bg-slate-100 text-slate-700 border border-slate-200/50 px-3 py-1 text-xs font-bold rounded-full">
                                Diarsipkan
                            </span>
                        @elseif ($aduan->status_aduan === 'terkirim')
                            <span class="inline-block bg-amber-50 text-amber-600 border border-amber-200/50 px-3 py-1 text-xs font-bold rounded-full">
                                Belum Ditangani
                            </span>
                        @elseif ($aduan->status_aduan === 'diproses')
                            <span class="inline-block bg-blue-50 text-blue-600 border border-blue-200/50 px-3 py-1 text-xs font-bold rounded-full">
                                Sedang Diproses
                            </span>
                        @else
                            <span class="inline-block bg-emerald-50 text-emerald-600 border border-emerald-200/50 px-3 py-1 text-xs font-bold rounded-full">
                                Ditanggapi
                            </span>
                        @endif
                    </div>
                </div>

                <div class="text-sm text-slate-500 flex items-center gap-1.5">
                    <i class="fa-regular fa-clock"></i>
                    <span>{{ $aduan->created_at->locale('id')->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>

                <hr class="border-slate-100">

                <div class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">
                    {{ $aduan->isi_aduan }}
                </div>

                @if ($aduan->lampiran)
                    <div class="border-t border-slate-100 pt-6">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Lampiran (1)</p>
                        @php
                            $ext = strtolower(pathinfo($aduan->lampiran, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['png', 'jpg', 'jpeg']);
                        @endphp
                        
                        @if ($isImage)
                            <div class="max-w-md overflow-hidden rounded-2xl border border-slate-100 bg-slate-50/50 p-2">
                                <a href="{{ asset('storage/' . $aduan->lampiran) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $aduan->lampiran) }}" alt="Lampiran" class="w-full h-auto object-cover rounded-xl hover:scale-[1.01] transition duration-200">
                                </a>
                            </div>
                        @else
                            <a href="{{ asset('storage/' . $aduan->lampiran) }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-emerald-600 hover:text-emerald-700 transition">
                                <i class="fa-solid fa-file-pdf text-base"></i>
                                Unduh Dokumen Pendukung (PDF)
                            </a>
                        @endif
                    </div>
                @endif

            </div>

            <!-- Riwayat Tanggapan Timeline -->
            <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm">
                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3 mb-6">Riwayat Tanggapan</h3>
                
                <div class="relative pl-6 border-l border-slate-100 space-y-6">
                    
                    <!-- First node: Aduan Dibuat -->
                    <div class="relative">
                        <span class="absolute -left-[35px] top-0.5 flex h-[18px] w-[18px] items-center justify-center rounded-full bg-slate-50 text-slate-500 ring-4 ring-white border border-slate-200">
                            <i class="fa-solid fa-circle text-[8px]"></i>
                        </span>
                        <div>
                            <div class="flex items-center justify-between gap-4">
                                <h4 class="text-sm font-bold text-slate-800">Aduan Dibuat</h4>
                                <span class="text-xs text-slate-400 font-semibold">{{ $aduan->created_at->locale('id')->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Aduan diterima oleh sistem dan menunggu verifikasi.</p>
                        </div>
                    </div>

                    <!-- Subsequent responses -->
                    @foreach ($aduan->tanggapan as $respon)
                        <div class="relative">
                            <span class="absolute -left-[35px] top-0.5 flex h-[18px] w-[18px] items-center justify-center rounded-full bg-emerald-50 text-emerald-600 ring-4 ring-white border border-emerald-100">
                                <i class="fa-solid fa-circle-check text-xs"></i>
                            </span>
                            <div>
                                <div class="flex items-center justify-between gap-4">
                                    <h4 class="text-sm font-bold text-slate-800">Ditanggapi oleh {{ $respon->petugas->nama_petugas ?? 'Petugas' }}</h4>
                                    <span class="text-xs text-slate-400 font-semibold">{{ $respon->created_at->locale('id')->translatedFormat('d M Y, H:i') }}</span>
                                </div>
                                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 mt-3 text-slate-600 text-xs leading-relaxed whitespace-pre-line relative">
                                    <div class="absolute right-4 top-2 opacity-5">
                                        <i class="fa-solid fa-quote-right text-4xl"></i>
                                    </div>
                                    <p class="font-bold text-[10px] uppercase tracking-wider text-slate-400 mb-1">Pesan Tanggapan:</p>
                                    {{ $respon->isi_tanggapan }}
                                    <p class="mt-2 text-[10px] font-bold text-slate-400">
                                        Status setelah respon: 
                                        <span class="text-emerald-700 font-extrabold uppercase">{{ $respon->status_setelah_respon }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($aduan->tanggapan->isEmpty())
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 text-center text-slate-400 ml-2">
                            <i class="fa-regular fa-hourglass text-3xl mb-2 text-slate-300"></i>
                            <p class="text-xs font-semibold">Belum ada tanggapan dari petugas.</p>
                            <p class="text-[10px] text-slate-400 mt-1">Silakan gunakan tombol 'Tanggapi Aduan' untuk merespon aduan ini.</p>
                        </div>
                    @endif

                </div>
            </div>

        </div>

        <!-- Right: Submitter profile & metadata -->
        <div class="space-y-6">
            
            <!-- Submitter Profile Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3">Informasi Pelapor</h3>
                
                <div class="flex items-center gap-4">
                    <!-- Photo -->
                    @if ($anggota->foto)
                        <img src="{{ asset('storage/'.$anggota->foto) }}" alt="Avatar" class="h-14 w-14 rounded-full border border-slate-200 object-cover shadow-sm">
                    @else
                        <span class="flex h-14 w-14 items-center justify-center rounded-full bg-[#ffd56b]/20 text-[#0e1f30] font-bold text-xl border border-[#ffd56b]/40">
                            {{ mb_substr($anggota->nama_lengkap, 0, 1) }}
                        </span>
                    @endif
                    
                    <div>
                        <h4 class="font-bold text-slate-800 text-base leading-snug">{{ $anggota->nama_lengkap }}</h4>
                        <p class="text-xs text-slate-400 mt-1">{{ $anggota->kalangan ?? 'Umum' }}</p>
                    </div>
                </div>

                <hr class="border-slate-100">

                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-xs font-bold text-slate-400">Email</p>
                        <p class="text-slate-700 mt-0.5 font-medium truncate" title="{{ $anggota->user->email }}">{{ $anggota->user->email }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-slate-400">Nomor Anggota</p>
                        <p class="text-slate-700 mt-0.5 font-mono font-semibold">{{ $anggota->no_anggota }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-slate-400">Riwayat Aduan</p>
                        <a href="{{ route('petugas.aduan.index', ['search' => $anggota->nama_lengkap]) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 hover:text-emerald-700 transition hover:underline mt-1">
                            Lihat {{ $riwayatCount }} aduan lainnya
                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Metadata Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3">Metadata Aduan</h3>
                
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-xs font-bold text-slate-400">ID Aduan</p>
                        <p class="text-slate-700 mt-0.5 font-mono font-semibold">{{ $aduan->kode_aduan }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-slate-400">Kategori</p>
                        <div class="mt-1.5">
                            <span class="inline-block bg-slate-100 text-slate-700 border border-slate-200/50 px-3 py-0.5 text-xs font-semibold rounded-full">
                                {{ $aduan->kategori_aduan ?? 'Lainnya' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-slate-400">Tingkat Prioritas</p>
                        <div class="flex items-center gap-1.5 mt-1.5 text-slate-700 font-semibold">
                            @if ($aduan->prioritas === 'tinggi')
                                <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                                <span>Tinggi</span>
                            @elseif ($aduan->prioritas === 'rendah')
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                <span>Rendah</span>
                            @else
                                <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                                <span>Menengah</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-slate-400">Lokasi Kejadian</p>
                        <p class="text-slate-600 mt-1">Perpustakaan SIPADI Bukittinggi</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
