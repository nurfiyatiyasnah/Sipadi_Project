@extends('layouts.petugas')

@section('title', 'Edit Layanan')

@section('content')
<div class="mx-auto max-w-[1180px] space-y-6">
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-800">SIPADI</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('petugas.layanan.index') }}" class="hover:text-slate-800">Layanan</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="font-semibold text-slate-800">Edit Layanan</span>
    </nav>

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Edit Layanan Perpustakaan</h2>
            <p class="mt-1 text-sm text-slate-500">Perbarui informasi, jadwal, prosedur, dan status publikasi layanan.</p>
        </div>
        <a href="{{ route('petugas.layanan.index') }}" class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-700 shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke Daftar
        </a>
    </div>

    @include('layanan._form', ['layanan' => $layanan])
</div>
@endsection
