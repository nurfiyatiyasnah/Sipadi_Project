@extends('layouts.petugas')

@section('title', 'Tambah Stok Buku')

@section('content')
    @livewire('tambah-stok-buku', ['id' => $id])
@endsection
