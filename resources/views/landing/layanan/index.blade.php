<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Perpustakaan - SIPADI Bukittinggi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f3f8fc] font-sans text-[#1e2f3f] antialiased">
    @include('layouts.public_navbar')

    <main class="mx-auto max-w-7xl px-5 py-10 lg:px-6">
        <section class="relative overflow-hidden rounded-xl bg-[#dcecf7] px-10 py-12">
            <div class="max-w-3xl">
                <h1 class="text-4xl font-extrabold tracking-tight text-[#004238]">Layanan Perpustakaan</h1>
                <p class="mt-4 max-w-2xl text-sm leading-6 text-[#38566a]">
                    Jelajahi berbagai layanan unggulan kami yang dirancang untuk mendukung kebutuhan literasi, riset, dan rekreasi edukatif Anda di lingkungan kota Bukittinggi yang kaya akan warisan budaya.
                </p>
            </div>
            <span class="absolute right-48 top-1/2 hidden h-4 w-8 rounded-full bg-[#b8d1de] lg:block"></span>
        </section>

        <section class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($layanan as $item)
                @php
                    $icons = ['fa-regular fa-id-card', 'fa-solid fa-book-open-reader', 'fa-solid fa-arrow-left', 'fa-solid fa-laptop-file', 'fa-solid fa-graduation-cap', 'fa-solid fa-flag'];
                    $icon = $icons[$loop->index % count($icons)];
                @endphp

                <article class="rounded-lg border border-[#d9e3ea] bg-white p-7 shadow-sm">
                    <span class="flex h-12 w-12 items-center justify-center rounded-md bg-[#dcecf7] text-[#004238]">
                        <i class="{{ $icon }} text-lg"></i>
                    </span>
                    <h2 class="mt-6 text-base font-extrabold text-[#23384b]">{{ $item->nama_layanan }}</h2>
                    <p class="mt-4 text-sm leading-6 text-[#4d6172]">{{ Str::limit($item->deskripsi ?: 'Informasi layanan perpustakaan SIPADI Bukittinggi.', 155) }}</p>
                    <a href="{{ route('layanan.show', $item->slug) }}" class="mt-7 inline-flex items-center gap-2 text-xs font-extrabold text-[#004238] hover:underline">
                        Lihat detail
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </article>
            @empty
                <div class="col-span-full rounded-lg border border-[#d9e3ea] bg-white p-10 text-center shadow-sm">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-md bg-[#dcecf7] text-[#004238]">
                        <i class="fa-solid fa-handshake-angle text-xl"></i>
                    </span>
                    <h2 class="mt-5 text-lg font-extrabold text-[#23384b]">Belum ada layanan aktif</h2>
                    <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-[#4d6172]">Layanan yang ditampilkan di sini akan otomatis mengikuti data aktif yang dikelola oleh petugas.</p>
                </div>
            @endforelse
        </section>
    </main>

    <footer class="mt-10 border-t border-[#d5e0e8] bg-[#f3f8fc]">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-5 py-9 text-xs text-[#30475a] lg:flex-row lg:items-end lg:justify-between lg:px-6">
            <div>
                <p class="text-lg font-extrabold leading-tight text-[#004238]">SIPADI Bukittinggi</p>
                <p class="mt-4 max-w-md leading-5">&copy; 2024 Dinas Perpustakaan dan Kearsipan Kota Bukittinggi. Seluruh Hak Cipta Dilindungi.</p>
            </div>
            <nav class="flex flex-wrap gap-x-7 gap-y-3 font-semibold">
                <a href="{{ route('tentang') }}" class="hover:text-[#004238]">Tentang Kami</a>
                <a href="#" class="hover:text-[#004238]">Kebijakan Privasi</a>
                <a href="{{ route('layanan.index') }}" class="hover:text-[#004238]">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-[#004238]">Peta Situs</a>
                <a href="{{ route('landing') }}#kontak" class="hover:text-[#004238]">Hubungi Kami</a>
            </nav>
        </div>
    </footer>
</body>
</html>
