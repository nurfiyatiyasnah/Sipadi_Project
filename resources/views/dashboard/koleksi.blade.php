@extends('layouts.petugas')

@section('title', 'Koleksi Buku')

@section('content')
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Koleksi Perpustakaan</h1>
        <p class="mt-1 text-gray-600">Data judul dan eksemplar buku yang tercatat di SIPADI.</p>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['label' => 'Total Judul', 'value' => $stats['judul']],
            ['label' => 'Total Eksemplar', 'value' => $stats['eksemplar']],
            ['label' => 'Sedang Dipinjam', 'value' => $stats['dipinjam']],
            ['label' => 'Tersedia', 'value' => $stats['tersedia']],
        ] as $stat)
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium uppercase tracking-wide text-gray-500">{{ $stat['label'] }}</p>
                <p class="mt-2 text-4xl font-bold text-gray-900">{{ number_format($stat['value']) }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
        <div class="flex flex-col justify-between gap-4 lg:flex-row">
            <form method="GET" action="{{ route('petugas.koleksi') }}" class="flex flex-col gap-3 sm:flex-row">
                <select name="kategori" class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id_kategori }}" @selected((string) request('kategori') === (string) $category->id_kategori)>
                            {{ $category->nama_kategori }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua status katalog</option>
                    <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
                </select>

                <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2 font-semibold text-white hover:bg-indigo-700">
                    Terapkan Filter
                </button>
            </form>

            <a href="{{ route('petugas.koleksi.export', request()->query()) }}" class="rounded-lg border border-gray-300 px-5 py-2 text-center font-semibold text-gray-700 hover:bg-gray-50">
                Ekspor CSV
            </a>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                    <tr>
                        <th class="px-6 py-4">Kode</th>
                        <th class="px-6 py-4">Judul</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Eksemplar</th>
                        <th class="px-6 py-4">Tersedia</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($books as $book)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono text-sm text-gray-600">{{ $book->kode_buku }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $book->judul }}</p>
                                <p class="text-sm text-gray-500">{{ $book->penulis ?: 'Penulis belum diisi' }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $book->kategori?->nama_kategori ?? '-' }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $book->eksemplar_count }}</td>
                            <td class="px-6 py-4 font-semibold text-emerald-700">{{ $book->eksemplar_tersedia_count }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                    {{ ucfirst($book->status_katalog ?? 'belum diatur') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                Belum ada koleksi yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t px-6 py-4">
            {{ $books->links() }}
        </div>
    </div>

    <p class="mt-6 text-sm text-gray-500">
        Persentase ketersediaan seluruh eksemplar: {{ $stats['persen'] }}%.
    </p>
@endsection
