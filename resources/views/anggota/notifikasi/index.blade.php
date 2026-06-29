@extends('layouts.public')
@section('title', 'Notifikasi Saya - SIPADI Bukittinggi')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
    <!-- Header Block -->
    <div class="border-b border-slate-200/60 pb-5 mb-8">
        <h1 class="font-serif text-4xl font-bold text-[#04241e] tracking-tight flex items-center gap-3">
            <i class="fa-solid fa-bell text-[#04241e]"></i>
            Notifikasi Saya
        </h1>
        <p class="text-sm text-slate-500 mt-2">Lihat seluruh riwayat pesan, info peminjaman, dan tanggapan dari petugas.</p>
    </div>

    <!-- Alert Success / Error -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold flex items-center gap-3">
            <i class="fa-solid fa-circle-exclamation text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Notifications List -->
    <div class="space-y-4">
        @forelse($notifikasis as $n)
            @php
                $isUnread = in_array($n->status_baca, ['belum_dibaca', 'Belum Dibaca']);
                $iconClass = 'fa-solid fa-bell text-slate-400 bg-slate-50 border-slate-100';
                $colorTheme = 'border-slate-100 bg-white hover:bg-slate-50/80';
                
                switch($n->jenis_notifikasi) {
                    case 'peminjaman_disetujui':
                        $iconClass = 'fa-solid fa-calendar-check text-emerald-600 bg-emerald-50 border-emerald-100';
                        break;
                    case 'peminjaman_ditolak':
                        $iconClass = 'fa-solid fa-circle-xmark text-rose-600 bg-rose-50 border-rose-100';
                        break;
                    case 'pengembalian_berhasil':
                        $iconClass = 'fa-solid fa-book text-blue-600 bg-blue-50 border-blue-100';
                        break;
                    case 'sanksi_aktif':
                        $iconClass = 'fa-solid fa-triangle-exclamation text-amber-600 bg-amber-50 border-amber-100';
                        break;
                }

                if ($isUnread) {
                    $colorTheme = 'border-emerald-200 bg-emerald-50/20 hover:bg-emerald-50/40';
                }
            @endphp

            <div class="border border-slate-200/80 rounded-3xl p-5 shadow-sm transition duration-200 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between {{ $colorTheme }}">
                <div class="flex gap-4 items-start">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl border text-xl flex-shrink-0 {{ $iconClass }}">
                        <!-- Icon mapped in PHP -->
                    </span>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-bold text-slate-800 text-base leading-snug">{{ $n->judul }}</h3>
                            @if ($isUnread)
                                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    Baru
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-600 mt-1 leading-relaxed">
                            {{ $n->isi }}
                        </p>
                        <div class="flex items-center gap-4 mt-2 text-xs text-slate-400 font-semibold">
                            <span class="flex items-center gap-1">
                                <i class="fa-regular fa-clock"></i>
                                {{ $n->dikirim_pada ? $n->dikirim_pada->diffForHumans() : $n->created_at->diffForHumans() }}
                            </span>
                            @if($n->dibaca_pada)
                                <span class="flex items-center gap-1 text-slate-400">
                                    <i class="fa-regular fa-envelope-open"></i>
                                    Dibaca {{ $n->dibaca_pada->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="w-full sm:w-auto flex-shrink-0 text-right">
                    <a href="{{ route('anggota.notifikasi.read', $n->id_notifikasi) }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#04241e] hover:bg-[#06342c] px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:shadow transition duration-200 w-full sm:w-auto">
                        <span>Buka Detail</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md shadow-[#04241e]/5 p-16 text-center">
                <span class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-300 text-3xl mx-auto mb-4">
                    <i class="fa-solid fa-bell-slash"></i>
                </span>
                <h4 class="text-lg font-bold text-slate-700">Belum ada notifikasi</h4>
                <p class="text-sm text-slate-400 mt-2 max-w-sm leading-relaxed mx-auto">
                    Seluruh informasi penting tentang peminjaman buku atau sanksi Anda akan ditampilkan di halaman ini.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifikasis->hasPages())
        <div class="mt-8">
            {{ $notifikasis->links() }}
        </div>
    @endif
</div>
@endsection
