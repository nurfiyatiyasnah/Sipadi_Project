@extends('layouts.petugas')

@section('title', 'Tambah Layanan Baru')

@section('content')
<div class="mx-auto max-w-[1180px] space-y-6">
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-800">Dashboard</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('petugas.layanan.index') }}" class="hover:text-slate-800">Layanan</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="font-semibold text-slate-800">Tambah Layanan</span>
    </nav>

    <div>
        <h2 class="text-2xl font-bold text-slate-900">Tambah Layanan Baru</h2>
        <p class="mt-1 text-sm text-slate-500">Daftarkan kategori layanan baru untuk memudahkan pengunjung perpustakaan.</p>
    </div>

    @include('layanan._form')
</div>
@endsection
