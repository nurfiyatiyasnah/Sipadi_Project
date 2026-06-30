<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Perpustakaan - SIPADI Bukittinggi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#eef7fd] font-sans text-[#183447] antialiased">
    @include('layouts.public_navbar')

    <main class="mx-auto max-w-7xl px-5 py-8 lg:px-6">
        <section class="border border-dashed border-[#66a7d5] bg-[#e6f2fb] px-6 py-7">
            <h1 class="text-3xl font-extrabold tracking-tight text-[#004238] lg:text-4xl">Profil Perpustakaan</h1>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-[#38566a]">
                Mengenal lebih dekat sejarah, visi, misi, dan struktur organisasi Dinas Perpustakaan dan Kearsipan Kota Bukittinggi.
            </p>
        </section>

        <section class="mt-8 grid gap-6 lg:grid-cols-[1fr_360px]">
            <article class="rounded-lg border border-[#d9e3ea] bg-white p-7 shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#d8f0d7] text-[#2f8c52]">
                        <i class="fa-solid fa-landmark text-sm"></i>
                    </span>
                    <h2 class="text-lg font-extrabold text-[#004238]">Sejarah</h2>
                </div>

                <div class="mt-6 space-y-4 text-sm leading-7 text-[#4d6172]">
                    <p>
                        Dinas Perpustakaan dan Kearsipan Kota Bukittinggi memiliki sejarah panjang dalam menjaga dan menyebarkan literasi di tengah masyarakat. Berawal dari sebuah perpustakaan kecil yang didirikan pasca kemerdekaan, institusi ini terus bertransformasi menjadi pusat informasi modern yang melayani berbagai lapisan masyarakat.
                    </p>
                    <p>
                        Seiring dengan perkembangan zaman, perpustakaan ini tidak hanya menyediakan koleksi buku cetak, tetapi juga merambah ke layanan digital melalui SIPADI Bukittinggi, memastikan akses ilmu pengetahuan yang tanpa batas bagi warga kota dan sekitarnya.
                    </p>
                </div>
            </article>

            <aside class="rounded-lg bg-[#004238] p-7 text-white shadow-sm">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-[#ffdc7c]">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </span>
                        <h2 class="text-lg font-extrabold">Visi</h2>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-200">
                        Terwujudnya masyarakat Bukittinggi yang cerdas, literat, dan berbudaya melalui layanan perpustakaan yang inovatif and inklusif.
                    </p>
                </div>

                <div class="mt-8">
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-[#ffdc7c]">
                            <i class="fa-solid fa-flag text-sm"></i>
                        </span>
                        <h2 class="text-lg font-extrabold">Misi</h2>
                    </div>
                    <ul class="mt-4 flex flex-col gap-4 text-sm leading-6 text-slate-200">
                        <li class="flex gap-3">
                            <i class="fa-solid fa-circle-check mt-1 text-xs text-[#ffdc7c]"></i>
                            <span>Meningkatkan kualitas dan kuantitas koleksi bahan perpustakaan yang relevan dengan kebutuhan masyarakat.</span>
                        </li>
                        <li class="flex gap-3">
                            <i class="fa-solid fa-circle-check mt-1 text-xs text-[#ffdc7c]"></i>
                            <span>Mengembangkan layanan perpustakaan berbasis teknologi informasi dan komunikasi.</span>
                        </li>
                    </ul>
                </div>
            </aside>
        </section>

        <section class="mt-8 rounded-lg border border-[#d9e3ea] bg-white p-7 shadow-sm">
            <div class="flex justify-between border-b pb-5">
                <h2 class="text-lg font-extrabold text-[#004238]">
                    <i class="fa-solid fa-sitemap mr-2"></i>Struktur Organisasi
                </h2>
                <p class="text-xs">Diperbarui Juni 2026</p>
            </div>

            <div class="mt-8 overflow-x-auto">
                <div class="min-w-[1100px] pb-4">
                    <div class="flex justify-center">
                        <div class="w-60 rounded-xl bg-white p-5 shadow-lg text-center border">
                            <img src="https://via.placeholder.com/140" class="mx-auto h-32 w-32 rounded-full border-4 border-[#2f7f83] object-cover" alt="Kepala Dinas">
                            <h3 class="mt-4 font-bold text-[#004238]">Nama Kepala Dinas</h3>
                            <p class="text-sm text-slate-500">Kepala Dinas</p>
                        </div>
                    </div>

                    <div class="mx-auto h-10 w-px bg-[#6ab1a5]"></div>

                    <div class="flex justify-center">
                        <div class="w-60 rounded-xl bg-white p-5 shadow-lg text-center border">
                            <img src="https://via.placeholder.com/130" class="mx-auto h-28 w-28 rounded-full border-4 border-[#6ab1a5] object-cover" alt="Sekretaris">
                            <h3 class="mt-4 font-bold text-[#004238]">Nama Sekretaris</h3>
                            <p class="text-sm text-slate-500">Sekretariat</p>
                        </div>
                    </div>

                    <div class="mx-auto h-8 w-px bg-[#6ab1a5]"></div>
                    <div class="mx-auto h-px w-[850px] bg-[#6ab1a5]"></div>

                    <div class="grid grid-cols-4 gap-6 pt-2">
                        <div class="flex flex-col items-center">
                            <div class="h-8 w-px bg-[#6ab1a5]"></div>
                            <div class="w-56 rounded-xl bg-white p-5 shadow-lg text-center border hover:-translate-y-1 transition">
                                <img src="https://via.placeholder.com/120" class="mx-auto h-24 w-24 rounded-full border-4 border-[#2f7f83] object-cover" alt="Kabid Perpustakaan">
                                <h3 class="mt-3 font-bold text-[#004238]">Nama Kepala Bidang</h3>
                                <p class="text-sm text-slate-500">Bidang Perpustakaan</p>
                            </div>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="h-8 w-px bg-[#6ab1a5]"></div>
                            <div class="w-56 rounded-xl bg-white p-5 shadow-lg text-center border hover:-translate-y-1 transition">
                                <img src="https://via.placeholder.com/120" class="mx-auto h-24 w-24 rounded-full border-4 border-[#2f7f83] object-cover" alt="Kabid Kearsipan">
                                <h3 class="mt-3 font-bold text-[#004238]">Nama Kepala Bidang</h3>
                                <p class="text-sm text-slate-500">Bidang Kearsipan</p>
                            </div>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="h-8 w-px bg-[#6ab1a5]"></div>
                            <div class="w-56 rounded-xl bg-white p-5 shadow-lg text-center border hover:-translate-y-1 transition">
                                <img src="https://via.placeholder.com/120" class="mx-auto h-24 w-24 rounded-full border-4 border-[#2f7f83] object-cover" alt="Kabid Layanan">
                                <h3 class="mt-3 font-bold text-[#004238]">Nama Kepala Bidang</h3>
                                <p class="text-sm text-slate-500">Bidang Layanan</p>
                            </div>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="h-8 w-px bg-[#6ab1a5]"></div>
                            <div class="w-56 rounded-xl bg-white p-5 shadow-lg text-center border hover:-translate-y-1 transition">
                                <img src="https://via.placeholder.com/120" class="mx-auto h-24 w-24 rounded-full border-4 border-[#2f7f83] object-cover" alt="Kabid Digitalisasi">
                                <h3 class="mt-3 font-bold text-[#004238]">Nama Kepala Bidang</h3>
                                <p class="text-sm text-slate-500">Bidang Digitalisasi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="mt-8 border-t border-[#d5e0e8] bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-5 py-9 text-xs text-[#30475a] lg:flex-row lg:items-end lg:justify-between lg:px-6">
            <div>
                <p class="text-lg font-extrabold leading-tight text-[#004238]">SIPADI Bukittinggi</p>
                <p class="mt-4 max-w-md leading-5">Layanan perpustakaan digital resmi Dinas Perpustakaan dan Kearsipan Kota Bukittinggi.</p>
                <p class="mt-3 leading-5">&copy; {{ date('Y') }} Dinas Perpustakaan dan Kearsipan Kota Bukittinggi. Seluruh Hak Cipta Dilindungi.</p>
            </div>
            <nav class="flex flex-wrap gap-x-7 gap-y-3 font-semibold">
                <a href="{{ route('tentang') }}" class="text-[#004238]">Tentang Kami</a>
                <a href="#" class="hover:text-[#004238]">Kebijakan Privasi</a>
                <a href="#" class="hover:text-[#004238]">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-[#004238]">Peta Situs</a>
                <a href="{{ route('landing') }}#kontak" class="hover:text-[#004238]">Hubungi Kami</a>
            </nav>
        </div>
    </footer>
</body>
</html>