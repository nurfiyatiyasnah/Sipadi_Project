@extends('layouts.app')

@section('title', 'Tambah Buku Baru')

@section('content')
<div class="space-y-6">
    <!-- Judul Halaman -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-2">Pendaftaran Koleksi Baru</h2>
        <p class="text-sm text-gray-500">Lengkapi data buku untuk menambah koleksi baru.</p>
    </div>

    <!-- Form Tambah Buku -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Grid Cover + Field -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Upload Cover -->
                <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-6 cursor-pointer hover:border-yellow-500">
                    <i class="fas fa-image text-gray-400 text-4xl mb-2"></i>
                    <p class="text-sm text-gray-500">Klik untuk unggah foto sampul</p>
                    <input type="file" name="cover" id="cover" accept="image/*" class="hidden">
                </div>

                <!-- Field Buku -->
                <div class="space-y-4">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700">Judul Buku</label>
                        <input type="text" name="judul" id="judul" required
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                    </div>

                    <div>
                        <label for="penulis" class="block text-sm font-medium text-gray-700">Penulis</label>
                        <input type="text" name="penulis" id="penulis" required
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                    </div>

                    <div>
                        <label for="penerbit" class="block text-sm font-medium text-gray-700">Penerbit</label>
                        <input type="text" name="penerbit" id="penerbit"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                    </div>

                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                        <input type="number" name="tahun" id="tahun" min="1000" max="9999"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                    </div>

                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN / ISSN</label>
                        <input type="text" name="isbn" id="isbn"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                    </div>

                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori Buku</label>
                        <select name="kategori" id="kategori" required
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Fiksi">Fiksi</option>
                            <option value="Non-Fiksi">Non-Fiksi</option>
                            <option value="Referensi">Referensi</option>
                            <option value="Majalah">Majalah</option>
                        </select>
                    </div>

                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi Rak</label>
                        <input type="text" name="lokasi" id="lokasi"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                    </div>

                    <div>
                        <label for="sinopsis" class="block text-sm font-medium text-gray-700">Deskripsi Singkat</label>
                        <textarea name="sinopsis" id="sinopsis" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500"></textarea>
                    </div>
                </div>
            </div>

            <!-- Status & Stok -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status Buku</label>
                    <select name="status" id="status"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="Tersedia">Tersedia untuk Dipinjam</option>
                        <option value="Dipinjam">Dipinjam</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>
                <div>
                    <label for="stok" class="block text-sm font-medium text-gray-700">Jumlah Stok</label>
                    <input type="number" name="stok" id="stok" min="1" value="1"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-between">
                <button type="reset"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Reset
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-gray-900 font-semibold rounded-lg hover:bg-yellow-600 transition">
                    Terbitkan Buku
                </button>
            </div>
        </form>
    </div>

    <!-- Info Box -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <i class="fas fa-shield-alt text-yellow-500 text-2xl mb-2"></i>
            <h4 class="font-bold text-gray-900">Keamanan Data</h4>
            <p class="text-sm text-gray-500">Data koleksi terlindungi dengan enkripsi.</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <i class="fas fa-search text-yellow-500 text-2xl mb-2"></i>
            <h4 class="font-bold text-gray-900">Indeksing Otomatis</h4>
            <p class="text-sm text-gray-500">Koleksi mudah dicari dengan sistem indexing.</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <i class="fas fa-mobile-alt text-yellow-500 text-2xl mb-2"></i>
            <h4 class="font-bold text-gray-900">Sinkronisasi Mobile</h4>
            <p class="text-sm text-gray-500">Akses koleksi dari perangkat mobile.</p>
        </div>
    </div>
</div>
@endsection
