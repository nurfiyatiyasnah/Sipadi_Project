@extends('layouts.petugas')

@section('title', 'Tambah Prestasi')

@section('content')
<div class="mx-auto max-w-[1180px] space-y-6">
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-800">Dashboard</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('petugas.prestasi.index') }}" class="hover:text-slate-800">Prestasi</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="font-semibold text-slate-800">Tambah Prestasi</span>
    </nav>

    <div>
        <h2 class="text-2xl font-bold text-slate-900">Tambah Prestasi</h2>
        <p class="mt-1 text-sm text-slate-500">Lengkapi informasi pencapaian atau penghargaan perpustakaan.</p>
    </div>

    @include('prestasi._form')
</div>
@endsection
