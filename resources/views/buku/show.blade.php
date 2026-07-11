@extends('layouts.petugas')

@section('title', 'Detail Buku')

@section('content')
    @livewire('buku-detail', ['id' => $id])
@endsection
