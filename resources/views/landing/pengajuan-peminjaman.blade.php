<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengajuan Peminjaman - {{ $buku->judul }} - SIPADI Bukittinggi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f5e9] font-sans text-[#061b3a] antialiased">

    <!-- Header / Navbar -->
    @include('layouts.public_navbar')

    <!-- Main Content -->
    <div class="mx-auto max-w-7xl min-w-0 px-4 sm:px-6 lg:px-12 py-8">

        <!-- Back Link -->
        <a href="{{ route('katalog.show', $buku->id_buku) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#1e463c] hover:text-[#15332c] transition mb-6">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Kembali ke Detail Buku
        </a>

        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="break-words text-3xl font-bold text-[#04241e]">Pengajuan Peminjaman</h1>
            <p class="text-sm text-slate-500 mt-1.5">Selesaikan pengajuan Anda untuk mengatur jadwal pengambilan.</p>
        </div>

        <!-- Alert Messages -->
        @if(session('error'))
            <div class="mb-6 flex items-start justify-between gap-3 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-sm font-semibold" id="error-alert">
                <div class="flex min-w-0 items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-base shrink-0 mt-0.5"></i>
                    <span class="min-w-0 break-words">{{ session('error') }}</span>
                </div>
                <button onclick="document.getElementById('error-alert').remove()" class="shrink-0 text-rose-400 hover:text-rose-700 transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <form method="POST" action="{{ route('peminjaman.store', $buku->id_buku) }}" id="form-pengajuan">
            @csrf

            <div class="grid min-w-0 grid-cols-1 gap-8 lg:grid-cols-[1fr_360px] items-start">

                <!-- Left Column: Form Cards -->
                <div class="min-w-0 space-y-6">

                    <!-- Data Anggota Card -->
                    <div class="min-w-0 bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                        <h2 class="break-words text-lg font-bold text-[#04241e] flex items-center gap-2 mb-5">
                            <i class="fa-solid fa-user text-sm text-[#1e463c]/60"></i>
                            Data Anggota
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5">Nama Lengkap</label>
                                <input 
                                    type="text" 
                                    readonly 
                                    value="{{ $anggota->nama_lengkap }}" 
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none cursor-not-allowed"
                                >
                            </div>

                            <!-- Nomor Anggota -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5">Nomor Anggota</label>
                                <input 
                                    type="text" 
                                    readonly 
                                    value="{{ $anggota->no_anggota }}" 
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none cursor-not-allowed"
                                >
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5">Email</label>
                                <input 
                                    type="text" 
                                    readonly 
                                    value="{{ $user->email }}" 
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none cursor-not-allowed"
                                >
                            </div>

                            <!-- Nomor Telepon -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5">Nomor Telepon</label>
                                <input 
                                    type="text" 
                                    readonly 
                                    value="{{ $anggota->no_telepon ?? '-' }}" 
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none cursor-not-allowed"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Pengajuan Card -->
                    <div class="min-w-0 bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                        <h2 class="break-words text-lg font-bold text-[#04241e] flex items-center gap-2 mb-5">
                            <i class="fa-regular fa-comment-dots text-sm text-[#1e463c]/60"></i>
                            Catatan Pengajuan (Opsional)
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <label for="catatan_pengajuan" class="block text-xs font-semibold text-slate-400 mb-1.5">Pesan/Catatan Tambahan</label>
                                <textarea 
                                    id="catatan_pengajuan" 
                                    name="catatan_pengajuan"
                                    rows="3"
                                    placeholder="Tulis pesan atau catatan tambahan untuk petugas jika ada..."
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-[#1e463c]/20 focus:border-[#1e463c] outline-none transition resize-none"
                                >{{ old('catatan_pengajuan') }}</textarea>
                                @error('catatan_pengajuan')
                                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 p-4 bg-emerald-50/50 border border-emerald-100 rounded-xl flex min-w-0 items-start gap-3">
                            <i class="fa-solid fa-circle-info text-emerald-600 mt-0.5 shrink-0"></i>
                            <p class="min-w-0 break-words text-xs text-emerald-800 leading-relaxed font-semibold">
                                Jadwal pengambilan akan dikirim setelah petugas menyetujui pengajuan peminjaman Anda.
                            </p>
                        </div>
                    </div>

                    <!-- Syarat & Ketentuan Card -->
                    <div class="min-w-0 bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                        <h2 class="break-words text-lg font-bold text-[#04241e] flex items-center gap-2 mb-5">
                            <i class="fa-solid fa-scale-balanced text-sm text-[#1e463c]/60"></i>
                            Syarat & Ketentuan
                        </h2>

                        <div class="break-words bg-slate-50 rounded-xl p-5 border border-slate-100 text-sm text-slate-600 leading-relaxed space-y-3">
                            <p>1. Anggota wajib membawa Kartu Tanda Anggota (KTA) saat mengambil buku.</p>
                            <p>2. Masa peminjaman adalah {{ $aturan?->lama_pinjam_hari ?? 14 }} hari kalender sejak tanggal pengambilan.</p>
                            <p>3. Keterlambatan pengembalian akan dikenakan sanksi tidak bisa meminjam buku sesuai berapa lama kamu terlambat mengembalikan buku, misal telat mengembalikan buku selama 3 hari = 3 hari kamu tidak dapat meminjam.</p>
                            <p>4. Kerusakan atau kehilangan buku menjadi tanggung jawab peminjam sepenuhnya, dan wajib mengganti dengan buku yang sama atau membayar denda seharga buku tersebut.</p>
                            <p>5. Jika buku tidak diambil sesuai jadwal yang telah ditentukan tanpa konfirmasi, pengajuan akan dibatalkan otomatis atau oleh petugas.</p>
                        </div>

                        <!-- Checkbox Agreement -->
                        <label class="flex items-start gap-3 mt-5 cursor-pointer group" for="setuju_syarat">
                            <input 
                                type="checkbox" 
                                id="setuju_syarat" 
                                name="setuju_syarat" 
                                value="1"
                                class="mt-0.5 h-4 w-4 rounded border-slate-300 text-[#1e463c] focus:ring-[#1e463c]/30 cursor-pointer shrink-0"
                            >
                            <span class="text-sm text-slate-600 leading-snug group-hover:text-slate-800 transition">
                                Saya telah membaca dan menyetujui <strong>Syarat & Ketentuan</strong> peminjaman yang berlaku di Dinas Perpustakaan dan Kearsipan Kota Bukittinggi.
                            </span>
                        </label>
                        @error('setuju_syarat')
                            <p class="text-xs text-rose-500 mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column: Summary Sidebar -->
                <div class="min-w-0 lg:sticky lg:top-24">
                    <div class="min-w-0 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <!-- Green Header -->
                        <div class="bg-[#1e463c] px-6 py-4 flex items-center gap-2.5">
                            <i class="fa-solid fa-clipboard-list text-white/80 text-base"></i>
                            <h3 class="text-white font-bold text-base">Ringkasan</h3>
                        </div>

                        <!-- Content -->
                        <div class="p-6 space-y-5">
                            <!-- Book Info Row -->
                            <div class="flex min-w-0 items-start gap-4">
                                <!-- Mini Book Cover -->
                                <div class="w-16 h-20 rounded-lg bg-slate-100 overflow-hidden shrink-0 shadow-sm">
                                    @if($buku->gambar_cover)
                                        @php
                                            $imageUrl = str_starts_with($buku->gambar_cover, 'http') ? $buku->gambar_cover : asset('storage/' . $buku->gambar_cover);
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="{{ $buku->judul }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-[#1e463c] to-[#0f3028] p-2 text-white flex flex-col justify-center">
                                            <p class="text-[8px] font-bold leading-tight line-clamp-3">{{ $buku->judul }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Book Details -->
                                <div class="flex-1 min-w-0">
                                    @if($tersediaCount > 0)
                                        <span class="inline-block bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-md mb-1.5">
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="inline-block bg-orange-50 text-orange-700 text-[10px] font-bold px-2 py-0.5 rounded-md mb-1.5">
                                            Tidak Tersedia
                                        </span>
                                    @endif
                                    <h4 class="break-words text-sm font-bold text-[#04241e] leading-snug line-clamp-2">{{ $buku->judul }}</h4>
                                    <p class="break-words text-xs text-slate-400 mt-0.5 font-semibold">{{ $buku->penulis }}</p>
                                    <p class="break-all text-[10px] text-slate-400 mt-0.5">ISBN: {{ $buku->isbn ?? '-' }}</p>
                                </div>
                            </div>

                            <hr class="border-slate-100">

                            <!-- Summary Details -->
                            <div class="space-y-3">
                                <div class="flex min-w-0 items-center justify-between gap-4 text-sm">
                                    <span class="text-slate-400 font-semibold">Jumlah Buku</span>
                                    <span class="font-bold text-[#04241e]">1 Eksemplar</span>
                                </div>
                                <div class="flex min-w-0 items-center justify-between gap-4 text-sm">
                                    <span class="text-slate-400 font-semibold">Durasi Pinjam</span>
                                    <span class="font-bold text-[#04241e]">{{ $aturan?->lama_pinjam_hari ?? 14 }} Hari</span>
                                </div>
                            </div>

                            <hr class="border-slate-100">

                            <!-- Submit Button -->
                            <button 
                                type="submit"
                                form="form-pengajuan"
                                class="w-full bg-[#1e463c] hover:bg-[#15332c] text-white font-bold rounded-xl py-3.5 px-4 flex items-center justify-center gap-2.5 transition duration-200 text-sm shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                id="btn-submit"
                            >
                                Ajukan Peminjaman
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </button>

                            <!-- Security Note -->
                            <p class="text-center text-[10px] text-slate-400 font-semibold flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-lock text-slate-400"></i>
                                Transaksi aman dan terenkripsi
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/60 py-12 mt-12">
        <div class="mx-auto max-w-7xl px-6 lg:px-12 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h3 class="font-serif font-bold text-xl text-[#04241e]">SIPADI Bukittinggi</h3>
                <p class="text-slate-500 text-sm mt-2 max-w-md">Sistem Informasi Perpustakaan & Arsip Digital Kota Bukittinggi. Menghubungkan masyarakat dengan warisan budaya dan literasi terbaik.</p>
            </div>
            <div class="flex flex-wrap gap-6 text-sm font-semibold text-slate-600">
                <a href="#" class="hover:text-[#04241e] transition">Tentang Kami</a>
                <a href="#" class="hover:text-[#04241e] transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-[#04241e] transition">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-[#04241e] transition">Peta Situs</a>
            </div>
        </div>
        <div class="mx-auto max-w-7xl px-6 lg:px-12 mt-8 pt-6 border-t border-slate-100 text-center text-xs text-slate-400">
            &copy; 2026 Dinas Perpustakaan dan Kearsipan Kota Bukittinggi. Seluruh Hak Cipta Dilindungi.
        </div>
    </footer>

    <!-- Checkbox Interaction script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('setuju_syarat');
            const submitBtn = document.getElementById('btn-submit');
            
            if (checkbox && submitBtn) {
                // Initialize button state
                submitBtn.disabled = !checkbox.checked;
                if (submitBtn.disabled) {
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
                
                // Toggle state on change
                checkbox.addEventListener('change', function() {
                    submitBtn.disabled = !this.checked;
                    if (submitBtn.disabled) {
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                });
            }
        });
    </script>

</body>
</html>
