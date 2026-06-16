@extends('layouts.app')

@section('title', 'Tambah Agenda Baru')

@section('content')
<div class="flex min-h-screen bg-gray-100">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="hidden lg:flex lg:flex-col w-64 bg-gradient-to-b from-slate-800 to-slate-900 text-white flex-shrink-0 fixed inset-y-0 left-0 z-30">
        {{-- Brand --}}
        <div class="px-6 py-6 border-b border-slate-700/50">
            <h2 class="text-lg font-bold tracking-wide text-amber-400">Bukittinggi</h2>
            <p class="text-xs text-slate-400 uppercase tracking-widest mt-0.5">Digital Archive</p>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <p class="px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 mb-3">Menu Utama</p>

            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                <i class="fas fa-th-large w-5 text-center text-slate-400"></i>
                Dashboard
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                <i class="fas fa-users w-5 text-center text-slate-400"></i>
                Anggota
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                <i class="fas fa-book w-5 text-center text-slate-400"></i>
                Buku
            </a>
            <a href="{{ route('agenda.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm bg-amber-500/10 text-amber-400 border-l-4 border-amber-400 font-medium">
                <i class="fas fa-calendar-alt w-5 text-center"></i>
                Agenda
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                <i class="fas fa-newspaper w-5 text-center text-slate-400"></i>
                Berita
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                <i class="fas fa-bullhorn w-5 text-center text-slate-400"></i>
                Aduan
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                <i class="fas fa-handshake w-5 text-center text-slate-400"></i>
                Peminjaman
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                <i class="fas fa-clock w-5 text-center text-slate-400"></i>
                Jadwal
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                <i class="fas fa-bell w-5 text-center text-slate-400"></i>
                Notifikasi
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                <i class="fas fa-box w-5 text-center text-slate-400"></i>
                Pengambilan
            </a>

            <p class="px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 mt-6 mb-3">Lainnya</p>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                <i class="fas fa-sitemap w-5 text-center text-slate-400"></i>
                Organisasi
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                <i class="fas fa-id-badge w-5 text-center text-slate-400"></i>
                Kepegawaian
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                <i class="fas fa-trophy w-5 text-center text-slate-400"></i>
                Prestasi
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-700/50 hover:text-white transition-all duration-200">
                <i class="fas fa-walking w-5 text-center text-slate-400"></i>
                Kunjungan
            </a>
        </nav>

        {{-- User Panel --}}
        <div class="px-4 py-4 border-t border-slate-700/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-amber-500 flex items-center justify-center text-sm font-bold text-slate-900">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-slate-400">Administrator Panel</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex-1 lg:ml-64">
        {{-- Top Bar --}}
        <header class="bg-white border-b border-gray-200 sticky top-0 z-20">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-3">
                    <button id="sidebar-toggle" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-bars text-gray-600"></i>
                    </button>
                    <h1 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-building mr-2 text-gray-400"></i>
                        Arsip Digital Bukittinggi
                    </h1>
                </div>
                <div class="flex items-center gap-4">
                    <button class="p-2 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-search text-gray-500"></i>
                    </button>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-1.5 border border-gray-300 rounded-full hover:bg-gray-50 transition">
                        <i class="fas fa-user text-gray-400 text-sm"></i>
                        <span class="text-sm text-gray-700 hidden sm:inline">Profile</span>
                    </a>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="p-6 lg:p-8">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="{{ route('dashboard') }}" class="hover:text-amber-600 transition">Dashboard</a>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                <span>Agenda</span>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                <span class="font-semibold text-gray-800">Tambah Agenda Baru</span>
            </nav>

            {{-- Page Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Buat Agenda & Event Baru</h1>
                    <p class="text-gray-500 mt-1">Publikasikan kegiatan perpustakaan dan literasi untuk warga Bukittinggi.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" form="agenda-form" onclick="document.getElementById('status_event').value='draft'; document.getElementById('agenda-form').submit();" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                        <i class="fas fa-save"></i>
                        Simpan Draft
                    </button>
                    <button type="button" form="agenda-form" onclick="document.getElementById('status_event').value='terbit'; document.getElementById('agenda-form').submit();" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-slate-800 to-slate-900 border-2 border-slate-800 rounded-xl text-sm font-semibold text-white hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-sm">
                        <i class="fas fa-paper-plane"></i>
                        Terbitkan Agenda
                    </button>
                </div>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3 animate-fade-in">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <p class="text-red-800 text-sm font-semibold">Terdapat kesalahan pada form:</p>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form id="agenda-form" action="{{ route('agenda.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="status_event" id="status_event" value="draft">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- ===== LEFT COLUMN ===== --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- Detail Kegiatan --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
                            <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                                <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-check text-amber-600 text-sm"></i>
                                </span>
                                Detail Kegiatan
                            </h3>

                            <div class="space-y-5">
                                {{-- Nama Kegiatan --}}
                                <div>
                                    <label for="judul_event" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Nama Kegiatan</label>
                                    <input
                                        type="text"
                                        name="judul_event"
                                        id="judul_event"
                                        value="{{ old('judul_event') }}"
                                        placeholder="Contoh: Festival Literasi Jam Gadang 2024"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition-all duration-200 bg-gray-50 focus:bg-white @error('judul_event') border-red-400 ring-1 ring-red-400 @enderror"
                                    >
                                    @error('judul_event')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Deskripsi --}}
                                <div>
                                    <label for="deskripsi" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Deskripsi Kegiatan</label>
                                    <textarea
                                        name="deskripsi"
                                        id="deskripsi"
                                        rows="5"
                                        placeholder="Tuliskan rincian kegiatan, tujuan, dan informasi penting lainnya..."
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition-all duration-200 bg-gray-50 focus:bg-white resize-none @error('deskripsi') border-red-400 ring-1 ring-red-400 @enderror"
                                    >{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Waktu & Lokasi --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
                            <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                                <span class="w-8 h-8 bg-rose-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-rose-500 text-sm"></i>
                                </span>
                                Waktu & Lokasi
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                {{-- Tanggal --}}
                                <div>
                                    <label for="tanggal_mulai" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Tanggal Kegiatan</label>
                                    <div class="relative">
                                        <input
                                            type="date"
                                            name="tanggal_mulai"
                                            id="tanggal_mulai"
                                            value="{{ old('tanggal_mulai') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition-all duration-200 bg-gray-50 focus:bg-white @error('tanggal_mulai') border-red-400 ring-1 ring-red-400 @enderror"
                                        >
                                    </div>
                                    @error('tanggal_mulai')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Waktu Mulai --}}
                                <div>
                                    <label for="jam_mulai" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Waktu Mulai</label>
                                    <div class="relative">
                                        <input
                                            type="time"
                                            name="jam_mulai"
                                            id="jam_mulai"
                                            value="{{ old('jam_mulai') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition-all duration-200 bg-gray-50 focus:bg-white @error('jam_mulai') border-red-400 ring-1 ring-red-400 @enderror"
                                        >
                                    </div>
                                    @error('jam_mulai')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Lokasi --}}
                            <div>
                                <label for="lokasi" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Lokasi Kegiatan</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                        <i class="fas fa-map-pin text-sm"></i>
                                    </span>
                                    <input
                                        type="text"
                                        name="lokasi"
                                        id="lokasi"
                                        value="{{ old('lokasi') }}"
                                        placeholder="Gedung Perpustakaan, Lantai 2 / Zoom Meeting"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition-all duration-200 bg-gray-50 focus:bg-white @error('lokasi') border-red-400 ring-1 ring-red-400 @enderror"
                                    >
                                </div>
                                @error('lokasi')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ===== RIGHT COLUMN ===== --}}
                    <div class="space-y-6">

                        {{-- Poster Kegiatan --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
                            <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                                <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-blue-600 text-sm"></i>
                                </span>
                                Poster Kegiatan
                            </h3>

                            {{-- Upload Area --}}
                            <div
                                id="upload-area"
                                class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-amber-400 hover:bg-amber-50/30 transition-all duration-300 group"
                                onclick="document.getElementById('gambar').click()"
                            >
                                <input
                                    type="file"
                                    name="gambar"
                                    id="gambar"
                                    accept="image/png,image/jpeg,image/jpg"
                                    class="hidden"
                                    onchange="previewImage(event)"
                                >

                                {{-- Default State --}}
                                <div id="upload-placeholder" class="space-y-3">
                                    <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center mx-auto group-hover:bg-amber-100 transition-colors duration-300">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 group-hover:text-amber-500 transition-colors duration-300"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">Pilih File Poster</p>
                                        <p class="text-xs text-gray-400 mt-1">Atau tarik dan lepas di sini</p>
                                    </div>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">PNG, JPG up to 5MB</p>
                                </div>

                                {{-- Preview State --}}
                                <div id="upload-preview" class="hidden">
                                    <img id="preview-img" src="" alt="Preview" class="max-h-48 mx-auto rounded-lg shadow-sm">
                                    <p id="preview-name" class="text-xs text-gray-500 mt-3"></p>
                                    <button type="button" onclick="event.stopPropagation(); clearImage()" class="mt-2 text-xs text-red-500 hover:text-red-700 font-medium transition">
                                        <i class="fas fa-times mr-1"></i>Hapus Gambar
                                    </button>
                                </div>
                            </div>
                            @error('gambar')
                                <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pengaturan Publikasi --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
                            <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                                <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-cog text-purple-600 text-sm"></i>
                                </span>
                                Pengaturan Publikasi
                            </h3>

                            <div class="space-y-5">
                                {{-- Status Event --}}
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Status Event</label>
                                    <select
                                        id="status_event_select"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition-all duration-200 bg-gray-50 focus:bg-white appearance-none cursor-pointer"
                                        onchange="document.getElementById('status_event').value = this.value"
                                    >
                                        <option value="draft" {{ old('status_event', 'draft') === 'draft' ? 'selected' : '' }}>Draft (Hanya Admin)</option>
                                        <option value="terbit" {{ old('status_event') === 'terbit' ? 'selected' : '' }}>Terbit (Publik)</option>
                                    </select>
                                </div>

                                {{-- Kategori --}}
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Kategori</label>
                                    <input type="hidden" name="kategori" id="kategori-input" value="{{ old('kategori', '') }}">

                                    <div id="kategori-tags" class="flex flex-wrap gap-2 mb-3">
                                        {{-- Tags will be rendered by JS --}}
                                    </div>

                                    <div class="flex gap-2">
                                        <input
                                            type="text"
                                            id="kategori-new"
                                            placeholder="Tambah kategori..."
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition-all duration-200 bg-gray-50 focus:bg-white"
                                            onkeypress="if(event.key==='Enter'){event.preventDefault(); addKategori()}"
                                        >
                                        <button type="button" onclick="addKategori()" class="w-10 h-10 bg-gray-100 hover:bg-amber-100 border border-gray-300 hover:border-amber-400 rounded-lg flex items-center justify-center text-gray-500 hover:text-amber-600 transition-all duration-200">
                                            <i class="fas fa-plus text-sm"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Tampilkan di Beranda --}}
                                <div class="pt-3 border-t border-gray-100">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="relative">
                                            <input
                                                type="checkbox"
                                                name="tampilkan_beranda"
                                                value="1"
                                                {{ old('tampilkan_beranda') ? 'checked' : '' }}
                                                class="sr-only peer"
                                            >
                                            <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:bg-amber-500 peer-checked:border-amber-500 transition-all duration-200 flex items-center justify-center group-hover:border-amber-400">
                                                <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-700 group-hover:text-gray-900 transition-colors">Tampilkan di Beranda Utama</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

{{-- Mobile Sidebar Overlay --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden" onclick="toggleSidebar()"></div>

@push('scripts')
<script>
    // ===== Sidebar Toggle =====
    const sidebar = document.querySelector('aside');
    const overlay = document.getElementById('sidebar-overlay');
    const toggleBtn = document.getElementById('sidebar-toggle');

    function toggleSidebar() {
        sidebar.classList.toggle('hidden');
        sidebar.classList.toggle('lg:flex');
        overlay.classList.toggle('hidden');
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.remove('hidden');
            sidebar.classList.remove('lg:flex');
            sidebar.classList.add('flex');
            overlay.classList.remove('hidden');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.add('hidden');
            sidebar.classList.add('lg:flex');
            sidebar.classList.remove('flex');
            overlay.classList.add('hidden');
        });
    }

    // ===== Image Preview =====
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        const placeholder = document.getElementById('upload-placeholder');
        const preview = document.getElementById('upload-preview');
        const previewImg = document.getElementById('preview-img');
        const previewName = document.getElementById('preview-name');

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewName.textContent = file.name;
            placeholder.classList.add('hidden');
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    function clearImage() {
        const input = document.getElementById('gambar');
        const placeholder = document.getElementById('upload-placeholder');
        const preview = document.getElementById('upload-preview');

        input.value = '';
        placeholder.classList.remove('hidden');
        preview.classList.add('hidden');
    }

    // ===== Drag & Drop =====
    const uploadArea = document.getElementById('upload-area');

    ['dragenter', 'dragover'].forEach(event => {
        uploadArea.addEventListener(event, function(e) {
            e.preventDefault();
            uploadArea.classList.add('border-amber-400', 'bg-amber-50/50');
        });
    });

    ['dragleave', 'drop'].forEach(event => {
        uploadArea.addEventListener(event, function(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-amber-400', 'bg-amber-50/50');
        });
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const input = document.getElementById('gambar');
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            previewImage({ target: { files: [file] } });
        }
    });

    // ===== Kategori Tags =====
    let kategoriList = [];

    function initKategori() {
        const existing = document.getElementById('kategori-input').value;
        if (existing) {
            kategoriList = existing.split(',').map(k => k.trim()).filter(k => k);
        } else {
            kategoriList = ['Literasi', 'Workshop', 'Pameran'];
            syncKategori();
        }
        renderKategori();
    }

    function renderKategori() {
        const container = document.getElementById('kategori-tags');
        container.innerHTML = '';

        kategoriList.forEach(function(kat, index) {
            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 cursor-default bg-amber-100 text-amber-800 border border-amber-200 hover:bg-amber-200';
            tag.innerHTML = kat + ' <button type="button" onclick="removeKategori(' + index + ')" class="ml-0.5 text-amber-500 hover:text-red-500 transition-colors"><i class="fas fa-times text-xs"></i></button>';
            container.appendChild(tag);
        });
    }

    function addKategori() {
        const input = document.getElementById('kategori-new');
        const value = input.value.trim();
        if (value && !kategoriList.includes(value)) {
            kategoriList.push(value);
            syncKategori();
            renderKategori();
        }
        input.value = '';
        input.focus();
    }

    function removeKategori(index) {
        kategoriList.splice(index, 1);
        syncKategori();
        renderKategori();
    }

    function syncKategori() {
        document.getElementById('kategori-input').value = kategoriList.join(',');
    }

    // ===== Custom Checkbox =====
    document.querySelectorAll('.peer').forEach(function(checkbox) {
        const visual = checkbox.nextElementSibling;
        if (!visual) return;

        function updateVisual() {
            const icon = visual.querySelector('i');
            if (!icon) return;
            if (checkbox.checked) {
                visual.classList.add('bg-amber-500', 'border-amber-500');
                visual.classList.remove('border-gray-300');
                icon.classList.remove('opacity-0');
                icon.classList.add('opacity-100');
            } else {
                visual.classList.remove('bg-amber-500', 'border-amber-500');
                visual.classList.add('border-gray-300');
                icon.classList.add('opacity-0');
                icon.classList.remove('opacity-100');
            }
        }

        checkbox.addEventListener('change', updateVisual);
        updateVisual();
    });

    // Initialize on load
    document.addEventListener('DOMContentLoaded', initKategori);
</script>
@endpush
@endsection
