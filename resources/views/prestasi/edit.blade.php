@extends('layouts.petugas')

@section('title', 'Edit Prestasi')

@section('content')
<div class="mx-auto max-w-[1180px] space-y-6">
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('petugas.dashboard') }}" class="hover:text-slate-800">Dashboard</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('petugas.prestasi.index') }}" class="hover:text-slate-800">Prestasi</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="font-semibold text-slate-800">Edit Prestasi</span>
    </nav>

    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Edit Prestasi</h2>
            <p class="mt-1 text-sm text-slate-500">Perbarui informasi prestasi perpustakaan.</p>
        </div>
        <a href="{{ route('petugas.prestasi.show', $prestasi) }}" class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 hover:bg-slate-50">
            <i class="fa-regular fa-eye"></i>
            Lihat Detail
        </a>
    </div>

    @include('prestasi._form')
</div>
@endsection
