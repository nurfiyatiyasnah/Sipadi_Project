@extends('layouts.petugas')
@section('title', 'Dashboard Admin')

@section('content')
<div class="mx-auto max-w-[1180px] space-y-8">
    <section class="overflow-hidden rounded-3xl bg-[#142b3d] text-white shadow-sm">
        <div class="grid gap-8 p-8 lg:grid-cols-[1fr_320px]">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-[#ffdc7c]">
                    <i class="fa-solid fa-shield-halved"></i>
                    Panel Operasional Admin
                </span>
                <h2 class="mt-5 font-serif text-4xl font-bold leading-tight">Selamat Pagi, Administrator</h2>
                <p class="mt-3 max-w-2xl text-lg text-slate-200">
                    Pantau layanan anggota, koleksi buku, peminjaman, dan aduan masyarakat dari satu dashboard SIPADI.
                </p>

                <div class="mt-8 grid max-w-2xl gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-300">Anggota</p>
                        <p class="mt-2 text-2xl font-bold">{{ number_format($stats['total_anggota']) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-300">Koleksi</p>
                        <p class="mt-2 text-2xl font-bold">{{ number_format($stats['koleksi_buku']) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-300">Aduan Baru</p>
                        <p class="mt-2 text-2xl font-bold">{{ number_format($stats['aduan_baru']) }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 text-[#071426]">
                <div class="flex items-center gap-4">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#ffdc7c] text-2xl">
                        <i class="fa-regular fa-calendar"></i>
                    </span>
                    <div>
                        <p class="text-sm font-bold uppercase tracking-widest text-slate-500">Hari Ini</p>
                        <p class="text-lg font-bold">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl bg-[#f6f5d8] p-5">
                    <p class="text-sm font-bold uppercase tracking-widest text-slate-600">Status Sistem</p>
                    <div class="mt-3 flex items-center gap-3">
                        <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                        <p class="font-bold text-emerald-700">Operasional</p>
                    </div>
                    <p class="mt-2 text-sm text-slate-600">Semua layanan utama siap digunakan admin.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['Total Anggota', $stats['total_anggota'], 'fa-solid fa-users', 'bg-blue-50 text-blue-700', 'Aktif'],
            ['Koleksi Buku', $stats['koleksi_buku'], 'fa-solid fa-book-open', 'bg-violet-50 text-violet-700', 'Terverifikasi'],
            ['Peminjaman Aktif', $stats['peminjaman_aktif'], 'fa-solid fa-handshake', 'bg-amber-50 text-amber-700', 'Berjalan'],
            ['Aduan Baru', $stats['aduan_baru'], 'fa-solid fa-triangle-exclamation', $stats['aduan_baru'] > 0 ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700', $stats['aduan_baru'] > 0 ? 'Perlu Ditinjau' : 'Aman'],
        ] as [$label, $value, $icon, $tone, $badge])
            <article class="rounded-3xl bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $tone }}">
                        <i class="{{ $icon }} text-xl"></i>
                    </span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">{{ $badge }}</span>
                </div>
                <p class="mt-6 text-sm font-bold uppercase tracking-[0.18em] text-slate-500">{{ $label }}</p>
                <p class="mt-3 font-serif text-4xl font-bold">{{ number_format($value) }}</p>
            </article>
        @endforeach
    </section>

    <section class="grid gap-8 xl:grid-cols-[1fr_340px]">
        <article class="rounded-3xl bg-white shadow-sm">
            <header class="flex h-20 items-center justify-between border-b border-slate-100 px-8">
                <div>
                    <h3 class="text-xl font-bold">Aktivitas Terkini</h3>
                    <p class="text-sm text-slate-500">Pergerakan data terbaru dari layanan SIPADI.</p>
                </div>
                <a href="#" class="text-sm font-bold text-[#806800]">Lihat Semua</a>
            </header>

            <div class="px-8 py-7">
                @foreach ($aktivitas_terkini as $aktivitas)
                    <div class="grid grid-cols-[48px_1fr_150px] gap-5">
                        <div class="flex flex-col items-center">
                            <span class="flex h-11 w-11 items-center justify-center rounded-2xl {{ $loop->first ? 'bg-[#ffdc7c]' : 'bg-[#142b3d] text-white' }}">
                                <i class="{{ $aktivitas['icon'] }}"></i>
                            </span>
                            @unless ($loop->last)
                                <span class="h-16 w-px bg-slate-200"></span>
                            @endunless
                        </div>
                        <div class="pb-6">
                            <h4 class="font-bold">{{ $aktivitas['judul'] }}</h4>
                            <p class="mt-1 text-slate-600">{{ $aktivitas['deskripsi'] }}</p>
                            @if ($aktivitas['status'])
                                <span class="mt-3 inline-flex rounded-full border border-slate-200 bg-slate-50 px-4 py-1 text-sm font-semibold text-slate-600">{{ $aktivitas['status'] }}</span>
                            @endif
                        </div>
                        <em class="text-right text-sm text-slate-500">{{ $aktivitas['waktu'] }}</em>
                    </div>
                @endforeach
            </div>
        </article>

        <div class="space-y-8">
            <article class="rounded-3xl bg-white p-6 shadow-sm">
                <h3 class="text-xl font-bold">Aksi Cepat</h3>
                <p class="mt-1 text-sm text-slate-500">Pintasan pekerjaan admin yang sering digunakan.</p>

                <div class="mt-6 grid grid-cols-2 gap-4">
                    @foreach ($aksi_cepat as $aksi)
                        <button class="flex h-[112px] flex-col items-center justify-center rounded-2xl border border-slate-200 font-bold transition hover:border-[#ffdc7c] hover:bg-[#ffdc7c]/20">
                            <i class="{{ $aksi['icon'] }} mb-3 text-3xl"></i>
                            <span class="text-center leading-tight">{{ $aksi['label'] }}</span>
                        </button>
                    @endforeach
                </div>
            </article>

            <article class="rounded-3xl bg-white p-6 shadow-sm">
                <h3 class="text-xl font-bold">Status Layanan</h3>
                <div class="mt-5 space-y-4">
                    @foreach ($status_layanan as $layanan)
                        <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-100 p-4">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $layanan['tone'] }}">
                                    <i class="{{ $layanan['icon'] }}"></i>
                                </span>
                                <p class="font-semibold">{{ $layanan['label'] }}</p>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $layanan['tone'] }}">{{ $layanan['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>
    </section>

    <section class="grid gap-8 xl:grid-cols-[360px_1fr]">
        <article class="rounded-3xl bg-[#ffdc7c] p-7 text-[#071426] shadow-sm">
            <p class="text-sm font-bold uppercase tracking-widest">Prioritas Hari Ini</p>
            <h3 class="mt-3 font-serif text-3xl font-bold">Fokus pekerjaan admin</h3>
            <p class="mt-3 text-slate-700">Gunakan daftar ini sebagai pengingat operasional utama sebelum menutup sesi kerja.</p>
        </article>

        <article class="rounded-3xl bg-white p-6 shadow-sm">
            <div class="grid gap-4 md:grid-cols-3">
                @foreach ($prioritas_hari_ini as $prioritas)
                    <div class="rounded-2xl border border-slate-100 p-5">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#142b3d] text-white">
                            <i class="{{ $prioritas['icon'] }}"></i>
                        </span>
                        <h4 class="mt-4 font-bold">{{ $prioritas['title'] }}</h4>
                        <p class="mt-2 text-sm text-slate-600">{{ $prioritas['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </article>
    </section>
</div>
@endsection
