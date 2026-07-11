<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Perpustakaan - SIPADI Bukittinggi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f5e9] font-sans text-[#061b3a] antialiased">
    @include('layouts.public_navbar')

    <!-- Header / Title Section -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 py-10">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-emerald-800">Tentang Kami</p>
                <h1 class="font-serif text-4xl lg:text-5xl font-bold text-[#04241e] mt-2">Profil Perpustakaan</h1>
                <p class="text-slate-500 mt-3 text-sm lg:text-base max-w-2xl">
                    Mengenal lebih dekat sejarah, visi, misi, dan struktur kepegawaian Dinas Perpustakaan dan Kearsipan Kota Bukittinggi.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="mx-auto max-w-7xl px-6 lg:px-12 pb-16 space-y-8">
        <section class="grid gap-6 lg:grid-cols-[1fr_360px]">
            <!-- Sejarah Card -->
            <article class="bg-white rounded-3xl p-6 lg:p-10 border border-slate-100 shadow-sm space-y-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-800">
                        <i class="fa-solid fa-landmark text-sm"></i>
                    </span>
                    <h2 class="font-serif text-2xl font-bold text-[#04241e]">Sejarah</h2>
                </div>

                <div class="space-y-4 text-sm lg:text-base leading-relaxed text-slate-600 font-sans border-t border-slate-100/60 pt-6">
                    <p>
                        Dinas Perpustakaan dan Kearsipan Kota Bukittinggi memiliki sejarah panjang dalam menjaga dan menyebarkan literasi di tengah masyarakat. Berawal dari sebuah perpustakaan kecil yang didirikan pasca kemerdekaan, institusi ini terus bertransformasi menjadi pusat informasi modern yang melayani berbagai lapisan masyarakat.
                    </p>
                    <p>
                        Seiring dengan perkembangan zaman, perpustakaan ini tidak hanya menyediakan koleksi buku cetak, tetapi juga merambah ke layanan digital melalui SIPADI Bukittinggi, memastikan akses ilmu pengetahuan yang tanpa batas bagi warga kota dan sekitarnya.
                    </p>
                </div>
            </article>

            <!-- Visi & Misi Sidebar -->
            <aside class="bg-[#04241e] rounded-3xl p-6 lg:p-8 text-white shadow-sm space-y-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 border-b border-white/10 pb-4 mb-4">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-[#ffdc7c]">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </span>
                        <h2 class="font-serif text-xl font-bold">Visi</h2>
                    </div>
                    <p class="text-sm leading-relaxed text-slate-300">
                        Terwujudnya masyarakat Bukittinggi yang cerdas, literat, dan berbudaya melalui layanan perpustakaan yang inovatif and inklusif.
                    </p>
                </div>

                <div class="border-t border-white/10 pt-6">
                    <div class="flex items-center gap-3 border-b border-white/10 pb-4 mb-4">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-[#ffdc7c]">
                            <i class="fa-solid fa-flag text-sm"></i>
                        </span>
                        <h2 class="font-serif text-xl font-bold">Misi</h2>
                    </div>
                    <ul class="flex flex-col gap-4 text-sm leading-relaxed text-slate-300">
                        <li class="flex gap-3">
                            <i class="fa-solid fa-circle-check mt-1 text-xs text-[#ffdc7c] shrink-0"></i>
                            <span>Meningkatkan kualitas dan kuantitas koleksi bahan perpustakaan yang relevan dengan kebutuhan masyarakat.</span>
                        </li>
                        <li class="flex gap-3">
                            <i class="fa-solid fa-circle-check mt-1 text-xs text-[#ffdc7c] shrink-0"></i>
                            <span>Mengembangkan layanan perpustakaan berbasis teknologi informasi dan komunikasi.</span>
                        </li>
                    </ul>
                </div>
            </aside>
        </section>

        <!-- Struktur Kepegawaian -->
        <section class="bg-white rounded-3xl p-6 lg:p-10 border border-slate-100 shadow-sm">
            <div class="flex justify-between items-center border-b pb-5">
                <h2 class="font-serif text-2xl font-bold text-[#04241e] flex items-center gap-2">
                    <i class="fa-solid fa-sitemap text-emerald-800"></i>Struktur Kepegawaian
                </h2>
                <p class="text-xs font-semibold text-slate-400">Bagan Kepegawaian Tahun 2026</p>
            </div>

            <div class="mt-8 flex flex-wrap justify-center gap-8">
                @forelse($strukturKepegawaian as $org)
                    <div class="w-60 rounded-3xl bg-white p-5 shadow-md text-center border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition duration-200">
                        @if($org->foto)
                            <img src="{{ asset('storage/' . $org->foto) }}" class="mx-auto h-32 w-32 rounded-full border-4 border-emerald-800 object-cover" alt="{{ $org->jabatan }}">
                        @else
                            <div class="mx-auto flex h-32 w-32 items-center justify-center rounded-full border-4 border-emerald-800 bg-emerald-50 text-emerald-800">
                                <i class="fa-solid fa-user text-4xl"></i>
                            </div>
                        @endif
                        <h3 class="mt-4 font-bold text-[#04241e]">{{ $org->nama }}</h3>
                        <p class="text-sm text-slate-400 mt-1 font-semibold">{{ $org->jabatan }}</p>
                    </div>
                @empty
                    <p class="text-slate-500 text-center w-full py-8">Struktur kepegawaian belum ditambahkan oleh admin.</p>
                @endforelse
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 mt-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
            <div class="flex flex-col lg:flex-row justify-between items-start gap-8">
                <!-- Branding -->
                <div>
                    <a href="{{ route('landing') }}" class="flex items-center gap-3 hover:opacity-90 transition">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#04241e] text-[#ffdc7c]">
                            <i class="fa-solid fa-building-columns text-sm"></i>
                        </span>
                        <span class="font-serif font-bold text-lg text-[#04241e] tracking-tight">SIPADI</span>
                    </a>
                    <p class="mt-4 text-sm text-slate-500 max-w-sm leading-relaxed">
                        Sistem Informasi Perpustakaan dan Arsip Digital Terintegrasi Kota Bukittinggi. Menghubungkan masyarakat dengan sumber pengetahuan tanpa batas.
                    </p>
                </div>

                <!-- Footer Navigation -->
                <div class="flex flex-wrap gap-x-8 gap-y-4 text-sm font-semibold text-slate-600">
                    <a href="{{ route('tentang') }}" class="hover:text-[#04241e] transition">Tentang Kami</a>
                    <a href="#" class="hover:text-[#04241e] transition">Kebijakan Privasi</a>
                    <a href="{{ route('layanan.index') }}" class="hover:text-[#04241e] transition">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-[#04241e] transition">Peta Situs</a>
                    <a href="{{ route('landing') }}#kontak" class="hover:text-[#04241e] transition">Hubungi Kami</a>
                </div>
            </div>

            <!-- Copyright Area -->
            <div class="border-t border-slate-100 mt-8 pt-8 flex flex-col sm:flex-row justify-between text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} Dinas Perpustakaan dan Kearsipan Kota Bukittinggi. Seluruh Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>
</body>
</html>
