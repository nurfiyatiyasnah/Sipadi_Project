@extends('layouts.petugas')

@section('title', 'Edit Buku')

@section('content')
    @livewire('buku-edit', ['id' => $id])
@endsection
