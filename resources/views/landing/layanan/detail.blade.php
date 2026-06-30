<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $layanan->nama_layanan }} - SIPADI Bukittinggi</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f3f8fc] font-sans text-[#1e2f3f] antialiased">
    @include('layouts.public_navbar')

    <main class="mx-auto max-w-7xl px-5 py-6 lg:px-6">
        <nav class="flex flex-wrap items-center gap-2 text-xs font-semibold text-[#526879]">
            <a href="{{ route('layanan.index') }}" class="inline-flex items-center gap-2 hover:text-[#004238]">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                Kembali ke Daftar Layanan
            </a>
            <span>/</span>
            <span class="text-[#30475a]">{{ $layanan->nama_layanan }}</span>
        </nav>

        <section class="mt-5 grid gap-6 lg:grid-cols-[1fr_390px]">
            <div class="rounded-lg border border-[#d5e0e8] bg-white p-8 shadow-sm">
                <div class="flex items-start gap-4">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#dcefdc] text-[#2e8a57]">
                        <i class="fa-solid fa-book-open text-base"></i>
                    </span>
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-[#23384b]">{{ $layanan->nama_layanan }}</h1>
                        <p class="mt-4 max-w-3xl text-sm leading-6 text-[#4d6172]">
                            {{ $layanan->deskripsi ?: 'Informasi detail layanan Perpustakaan Umum Kota Bukittinggi.' }}
                        </p>
                    </div>
                </div>

                <div class="mt-7 grid gap-4 md:grid-cols-3">
                    <div class="rounded-md border border-[#d5e0e8] bg-[#f4f9fd] p-5">
                        <div class="flex items-center gap-2 text-[#55758a]">
                            <i class="fa-regular fa-clock text-sm"></i>
                            <span class="text-xs font-semibold">Jam Layanan</span>
                        </div>
                        <p class="mt-2 text-lg font-extrabold text-[#23384b]">{{ $layanan->jam_layanan ?: 'Menyesuaikan jadwal' }}</p>
                    </div>
                    <div class="rounded-md border border-[#d5e0e8] bg-[#f4f9fd] p-5">
                        <div class="flex items-center gap-2 text-[#55758a]">
                            <i class="fa-solid fa-headset text-sm"></i>
                            <span class="text-xs font-semibold">Kontak</span>
                        </div>
                        <p class="mt-2 text-lg font-extrabold text-[#23384b]">{{ $layanan->kontak_layanan ?: 'Petugas layanan' }}</p>
                    </div>
                    <div class="rounded-md border border-[#d5e0e8] bg-[#f4f9fd] p-5">
                        <div class="flex items-center gap-2 text-[#55758a]">
                            <i class="fa-solid fa-money-bill-wave text-sm"></i>
                            <span class="text-xs font-semibold">Biaya</span>
                        </div>
                        <p class="mt-2 text-lg font-extrabold text-[#23384b]">{{ $layanan->biaya ?: 'Gratis' }}</p>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-xl font-extrabold text-[#23384b]">Alur Layanan</h2>
                    <div class="mt-4 space-y-4">
                        @forelse ($procedures as $procedure)
                            <div class="relative flex gap-4">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#004238] text-sm font-extrabold text-white">{{ $loop->iteration }}</span>
                                <div class="flex-1 rounded-md border border-[#d5e0e8] bg-[#f4f9fd] px-5 py-4">
                                    <h3 class="font-extrabold text-[#23384b]">Langkah {{ $loop->iteration }}</h3>
                                    <p class="mt-1 text-sm leading-5 text-[#526879]">{{ $procedure }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="relative flex gap-4">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#004238] text-sm font-extrabold text-white">1</span>
                                <div class="flex-1 rounded-md border border-[#d5e0e8] bg-[#f4f9fd] px-5 py-4">
                                    <h3 class="font-extrabold text-[#23384b]">Hubungi Petugas</h3>
                                    <p class="mt-1 text-sm leading-5 text-[#526879]">Silakan hubungi petugas untuk mendapatkan arahan penggunaan layanan ini.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <section class="rounded-lg border border-[#d5e0e8] bg-white p-6 text-center shadow-sm">
                    <div class="mx-auto flex h-32 w-32 items-center justify-center overflow-hidden bg-[#f4e3c7]">
                        @if ($layanan->gambar)
                            <img src="{{ Storage::url($layanan->gambar) }}" alt="{{ $layanan->nama_layanan }}" class="h-full w-full object-cover">
                        @else
                            <div class="relative h-24 w-24 rotate-[-10deg] rounded-sm bg-[#073d3a] shadow-xl">
                                <div class="absolute left-2 top-0 h-full w-2 bg-black/20"></div>
                                <div class="absolute left-8 top-8 h-2 w-10 rounded-full bg-[#b6a56f]/70"></div>
                                <div class="absolute left-9 top-12 h-1.5 w-8 rounded-full bg-[#b6a56f]/50"></div>
                            </div>
                        @endif
                    </div>
                    <h2 class="mt-5 text-xl font-extrabold text-[#23384b]">Ingin Menggunakan Layanan?</h2>
                    <p class="mx-auto mt-2 max-w-xs text-sm leading-5 text-[#526879]">Anda dapat masuk sebagai anggota aktif untuk menggunakan layanan ini secara online.</p>
                    <div class="mt-6 space-y-3">
                        <a href="{{ route('login') }}" class="block rounded-md bg-[#004238] px-4 py-3 text-sm font-extrabold text-white hover:bg-[#06382f]">Masuk ke Akun</a>
                        <a href="{{ route('register') }}" class="block rounded-md border border-[#004238] px-4 py-3 text-sm font-extrabold text-[#004238] hover:bg-[#eef7f2]">Daftar Anggota Baru</a>
                    </div>
                </section>

                <section id="syarat-ketentuan" class="rounded-lg border border-[#d5e0e8] bg-white p-6 shadow-sm">
                    <h2 class="flex items-center gap-2 text-base font-extrabold text-[#23384b]">
                        <i class="fa-solid fa-list-check text-sm text-[#004238]"></i>
                        Syarat & Ketentuan
                    </h2>
                    <ul class="mt-4 space-y-3 text-sm leading-5 text-[#526879]">
                        @forelse ($requirements as $requirement)
                            <li>{{ $requirement }}</li>
                        @empty
                            <li>Memiliki akun atau kartu anggota perpustakaan yang masih aktif.</li>
                            <li>Mengikuti ketentuan yang berlaku pada layanan ini.</li>
                        @endforelse
                    </ul>
                </section>

                <section id="jadwal-layanan" class="rounded-lg border border-[#d5e0e8] bg-white p-6 shadow-sm">
                    <h2 class="flex items-center gap-2 text-base font-extrabold text-[#23384b]">
                        <i class="fa-regular fa-clock text-sm text-[#004238]"></i>
                        Jam Layanan
                    </h2>
                    <dl class="mt-4 space-y-3 text-sm font-semibold text-[#526879]">
                        <div class="flex items-center justify-between gap-4">
                            <dt>Waktu Layanan</dt>
                            <dd class="text-right text-[#23384b]">{{ $layanan->jam_layanan ?: 'Mengikuti jam operasional perpustakaan' }}</dd>
                        </div>
                        @if ($layanan->kontak_layanan)
                            <div class="flex items-center justify-between gap-4">
                                <dt>Kontak</dt>
                                <dd class="text-right text-[#23384b]">{{ $layanan->kontak_layanan }}</dd>
                            </div>
                        @endif
                    </dl>
                </section>

                @if ($relatedLayanan->isNotEmpty())
                    <section class="rounded-lg border border-[#d5e0e8] bg-white p-6 shadow-sm">
                        <h2 class="text-base font-extrabold text-[#23384b]">Layanan Lainnya</h2>
                        <div class="mt-4 space-y-3">
                            @foreach ($relatedLayanan as $item)
                                <a href="{{ route('layanan.show', $item->slug) }}" class="block rounded-md border border-[#d5e0e8] px-4 py-3 text-sm font-bold text-[#004238] hover:bg-[#f4f9fd]">
                                    {{ $item->nama_layanan }}
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </aside>
        </section>
    </main>

    <footer class="mt-12 border-t border-[#d5e0e8] bg-[#f3f8fc]">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-5 py-9 text-xs text-[#30475a] lg:flex-row lg:items-end lg:justify-between lg:px-6">
            <div>
                <p class="text-lg font-extrabold leading-tight text-[#004238]">SIPADI Bukittinggi</p>
                <p class="mt-4 max-w-md leading-5">&copy; 2024 Dinas Perpustakaan dan Kearsipan Kota Bukittinggi. Seluruh Hak Cipta Dilindungi.</p>
            </div>
            <nav class="flex flex-wrap gap-x-7 gap-y-3 font-semibold">
                <a href="{{ route('landing') }}#kontak" class="hover:text-[#004238]">Tentang Kami</a>
                <a href="#" class="hover:text-[#004238]">Kebijakan Privasi</a>
                <a href="#syarat-ketentuan" class="hover:text-[#004238]">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-[#004238]">Peta Situs</a>
                <a href="{{ route('landing') }}#kontak" class="hover:text-[#004238]">Hubungi Kami</a>
            </nav>
        </div>
    </footer>
</body>
</html>
