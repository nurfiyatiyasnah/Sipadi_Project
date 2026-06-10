@extends('layouts.app')

@section('title', 'Koleksi Buku')

@section('content')

<!-- Header Halaman -->
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">
        Koleksi Perpustakaan
    </h2>
    <p class="text-gray-500 mt-1">
        Kelola khazanah literasi Kota Bukittinggi hari ini.
    </p>
</div>

<!-- Statistik -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm uppercase tracking-wider text-gray-500">
            Total Judul
        </p>
        <h3 class="text-4xl font-bold text-gray-800 mt-2">
            {{ $stats['judul'] }}
        </h3>
        <span class="text-green-600 text-sm font-medium">
            +12%
        </span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm uppercase tracking-wider text-gray-500">
            Total Eksemplar
        </p>
        <h3 class="text-4xl font-bold text-gray-800 mt-2">
            {{ $stats['eksemplar'] }}
        </h3>
        <span class="text-gray-500 text-sm">
            Stok Aman
        </span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm uppercase tracking-wider text-gray-500">
            Buku Dipinjam
        </p>
        <h3 class="text-4xl font-bold text-yellow-600 mt-2">
            {{ $stats['dipinjam'] }}
        </h3>
        <span class="text-gray-500 text-sm">
            Bulan Ini
        </span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm uppercase tracking-wider text-gray-500">
            Buku Tersedia
        </p>
        <h3 class="text-4xl font-bold text-gray-800 mt-2">
            {{ $stats['tersedia'] }}
        </h3>
        <span class="text-green-600 text-sm font-medium">
            {{ $stats['persen'] }}%
        </span>
    </div>

</div>

<!-- Toolbar -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6">

    <div class="flex flex-col lg:flex-row justify-between gap-4">

        <form method="GET"
              action="{{ route('admin.dashboard.koleksi') }}"
              class="flex flex-wrap gap-3">

            <select name="kategori"
                class="border border-gray-300 rounded-lg px-4 py-2">
                <option value="">Semua Kategori</option>

                @foreach($categories as $cat)
                    <option value="{{ $cat }}">
                        {{ $cat }}
                    </option>
                @endforeach
            </select>

            <select name="status"
                class="border border-gray-300 rounded-lg px-4 py-2">
                <option value="">Semua Status</option>
                <option value="Tersedia">Tersedia</option>
                <option value="Dipinjam">Dipinjam</option>
                <option value="Referensi">Referensi Saja</option>
            </select>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                Filter
            </button>

        </form>

        <div class="flex gap-3">

            <a href="{{ route('admin.dashboard.koleksi.export') }}"
                class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                Ekspor Laporan
            </a>

            <a href="{{ route('books.create') }}"
                class="bg-gray-900 text-white px-5 py-2 rounded-lg hover:bg-gray-800">
                + Tambah Koleksi Baru
            </a>

        </div>

    </div>

</div>

<!-- Tabel -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-gray-50 border-b">

                <tr class="text-left text-gray-600 text-sm uppercase">

                    <th class="px-6 py-4">Sampul</th>
                    <th class="px-6 py-4">Judul Buku</th>
                    <th class="px-6 py-4">Penulis</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Stok</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Aksi</th>

                </tr>

            </thead>

            <tbody>

            @foreach($books as $book)

                <tr class="border-b hover:bg-gray-50">

                    <td class="px-6 py-4">

                        <img
                            src="{{ asset('covers/'.$book->cover) }}"
                            class="w-14 h-20 object-cover rounded-md">

                    </td>

                    <td class="px-6 py-4">

                        <h4 class="font-semibold text-gray-800">
                            {{ $book->judul }}
                        </h4>

                        <p class="text-sm text-gray-500">
                            ISBN: {{ $book->isbn }}
                        </p>

                    </td>

                    <td class="px-6 py-4">
                        {{ $book->penulis }}
                    </td>

                    <td class="px-6 py-4">

                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">
                            {{ $book->kategori }}
                        </span>

                    </td>

                    <td class="px-6 py-4 font-semibold">
                        {{ $book->stok }}
                    </td>

                    <td class="px-6 py-4">

                        @if($book->status == 'Tersedia')
                            <span class="text-green-600 font-medium">
                                ● Tersedia
                            </span>

                        @elseif($book->status == 'Dipinjam')
                            <span class="text-yellow-600 font-medium">
                                ● Dipinjam
                            </span>

                        @else
                            <span class="text-red-600 font-medium">
                                ● Referensi Saja
                            </span>
                        @endif

                    </td>

                    <td class="px-6 py-4">

                        <a href="{{ route('books.show',$book->id) }}"
                            class="text-blue-600 hover:text-blue-800">
                            Detail
                        </a>

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center px-6 py-4">

        <small class="text-gray-500">
            Menampilkan
            {{ $books->firstItem() }}
            -
            {{ $books->lastItem() }}
            dari
            {{ $books->total() }}
            buku
        </small>

        {{ $books->links() }}

    </div>

</div>

<!-- Footer -->
<div class="mt-8 text-center text-sm text-gray-500">

    © 2024 Dinas Perpustakaan dan Kearsipan Kota Bukittinggi |
    <a href="#" class="hover:text-blue-600">Kebijakan Privasi</a> |
    <a href="#" class="hover:text-blue-600">Syarat & Ketentuan</a> |
    <a href="#" class="hover:text-blue-600">Panduan Admin</a>

</div>

@endsection