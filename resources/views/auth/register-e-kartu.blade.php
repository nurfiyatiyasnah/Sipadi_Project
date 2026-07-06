<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>E-Kartu - Registrasi SIPADI</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f5e9] font-sans text-[#061b3a]">
    <main class="min-h-screen bg-[radial-gradient(circle_at_top_left,_#ffffff_0,_#f6f5e9_42%,_#eef1df_100%)] px-6 py-8 lg:px-12">
        <div class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-7xl items-center gap-10 lg:grid-cols-[1fr_620px]">
            
            <!-- Tampilan Kiri (Sama seperti Langkah 2 Akun) -->
            <section class="hidden lg:block">
                <div class="max-w-xl">
                    <p class="text-sm font-bold uppercase tracking-widest text-[#806800]">Langkah 3 dari 3</p>
                    <h1 class="mt-3 font-serif text-6xl font-bold leading-[1.05]">
                        Buat Akun,<br>
                        <span class="text-[#8ea2d4]">Aktifkan E-Kartu.</span>
                    </h1>
                    <p class="mt-8 max-w-lg text-xl leading-9 text-slate-600">
                        Setelah akun dibuat, sistem akan menerbitkan e-kartu anggota digital dan membawa Anda ke halaman download.
                    </p>

                    <div class="mt-10 overflow-hidden rounded-[2rem] bg-[#061b3a] p-6 text-white shadow-2xl">
                        <div class="flex items-start justify-between gap-5">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#ffdc7c]">SIPADI Bukittinggi</p>
                                <h2 class="mt-2 text-2xl font-bold">Preview E-Kartu</h2>
                            </div>
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/15 text-3xl font-bold ring-1 ring-white/20">
                                {{ mb_substr($anggota->nama_lengkap, 0, 1) }}
                            </div>
                        </div>

                        <div class="mt-8 grid gap-5 rounded-3xl bg-white/10 p-6 text-sm">
                            <div>
                                <p class="text-slate-300">Nama Anggota</p>
                                <p class="mt-1 text-2xl font-bold">{{ $anggota->nama_lengkap }}</p>
                            </div>
                            <div>
                                <p class="text-slate-300">Nomor Kartu / NIK</p>
                                <p class="mt-1 font-mono text-xl font-bold">{{ $eKartu->no_anggota }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-slate-300">Kalangan</p>
                                    <p class="mt-1 font-semibold">{{ $eKartu->kalangan }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-300">Status</p>
                                    <p class="mt-1 font-semibold text-[#ffdc7c]">Siap Diterbitkan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tampilan Kanan (Menggunakan Gambar Kedua + Tombol Login) -->
            <section class="mx-auto w-full max-w-2xl rounded-[2rem] bg-white/95 p-6 shadow-2xl shadow-slate-900/10 sm:p-10">
                <div>
                    <p class="text-sm font-bold uppercase tracking-widest text-[#806800]">Langkah 3 dari 3</p>
                    <h2 class="mt-2 text-3xl font-bold">E-Kartu Anggota</h2>
                    <p class="mt-2 text-slate-500">Gunakan kartu digital ini untuk mengakses layanan SIPADI Bukittinggi.</p>
                </div>

                <!-- Progress Steps Indicator -->
                <div class="mt-8 grid gap-3 text-xs font-bold sm:grid-cols-3">
                    <div class="rounded-2xl bg-emerald-50 px-4 py-3 text-emerald-700">
                        <span class="block text-lg">01</span>
                        <span>Data Diri</span>
                    </div>
                    <div class="rounded-2xl bg-emerald-50 px-4 py-3 text-emerald-700">
                        <span class="block text-lg">02</span>
                        <span>Akun</span>
                    </div>
                    <div class="rounded-2xl bg-[#061b3a] px-4 py-3 text-white">
                        <span class="block text-lg text-white">03</span>
                        <span>E-Kartu</span>
                    </div>
                </div>

                <!-- Banner Registrasi Berhasil -->
                <div class="mt-8 mb-6 rounded-3xl bg-[#f8fafc] border border-slate-100 p-6 shadow-sm">
                    <p class="text-sm font-bold uppercase tracking-widest text-[#806800]">Registrasi Berhasil</p>
                    <h3 class="mt-2 font-serif text-2xl font-bold text-[#061b3a] leading-tight">E-Kartu Anggota Anda Sudah Aktif</h3>
                    <p class="mt-2 text-sm text-slate-600">Gunakan kartu digital ini untuk mengakses layanan SIPADI Bukittinggi.</p>
                </div>

                <!-- E-Kartu Card -->
                <div class="overflow-hidden rounded-[2rem] bg-[#061b3a] p-6 text-white shadow-2xl shadow-[#061b3a]/20">
                    <div class="flex flex-col justify-between gap-6 sm:flex-row">
                        <div class="flex-1">
                            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#ffdc7c]">SIPADI Bukittinggi</p>
                            <h3 class="mt-3 text-2xl font-bold">Kartu Anggota Digital</h3>

                            <dl class="mt-6 grid gap-4 rounded-2xl bg-white/10 p-5 text-xs">
                                <div>
                                    <dt class="text-slate-300">Nama Anggota</dt>
                                    <dd class="mt-1 text-xl font-bold">{{ $anggota->nama_lengkap }}</dd>
                                </div>
                                <div>
                                    <dt class="text-slate-300">Nomor Kartu / NIK</dt>
                                    <dd class="mt-1 font-mono text-lg font-bold">{{ $eKartu->no_anggota }}</dd>
                                </div>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <dt class="text-slate-300">Kalangan</dt>
                                        <dd class="mt-1 font-semibold">{{ $eKartu->kalangan }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-slate-300">Berlaku Sampai</dt>
                                        <dd class="mt-1 font-semibold">{{ $eKartu->masa_berlaku?->translatedFormat('d F Y') }}</dd>
                                    </div>
                                </div>
                            </dl>
                        </div>

                        <div class="flex min-w-[200px] flex-col justify-between rounded-2xl bg-white p-4 text-[#061b3a]">
                            <div class="flex justify-center">
                                @if ($anggota->foto)
                                    <img src="{{ asset('storage/'.$anggota->foto) }}" alt="Foto {{ $anggota->nama_lengkap }}" class="h-24 w-24 rounded-2xl object-cover ring-2 ring-[#ffdc7c]/70">
                                @else
                                    <div class="flex h-24 w-24 items-center justify-center rounded-2xl bg-[#f6f5e9] text-3xl font-bold ring-2 ring-[#ffdc7c]/70">
                                        {{ mb_substr($anggota->nama_lengkap, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4 rounded-xl bg-[#f6f5e9] p-3 text-center">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Kode Kartu</p>
                                <p class="mt-1 break-all font-mono text-[10px]">{{ $eKartu->barcode }}</p>
                            </div>
                            <div class="mt-3 rounded-full bg-emerald-50 px-3 py-1.5 text-center text-xs font-bold text-emerald-700">
                                Aktif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons: Download PDF & Login -->
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('anggota.e-kartu.download') }}" class="rounded-2xl bg-[#061b3a] px-5 py-3 text-sm font-semibold text-white shadow hover:bg-[#0b2a59] focus:outline-none focus:ring-2 focus:ring-[#061b3a] focus:ring-offset-2 transition duration-200">
                        Download PDF
                    </a>
                    <a href="{{ route('register.e-kartu.login') }}" class="rounded-2xl border border-[#061b3a] bg-white px-5 py-3 text-sm font-semibold text-[#061b3a] shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#061b3a] focus:ring-offset-2 transition duration-200">
                        Login
                    </a>
                </div>
            </section>

        </div>
    </main>
</body>
</html>
