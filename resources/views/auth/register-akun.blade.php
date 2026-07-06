<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Akun - Registrasi SIPADI</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f5e9] font-sans text-[#061b3a]">
    <main class="min-h-screen bg-[radial-gradient(circle_at_top_left,_#ffffff_0,_#f6f5e9_42%,_#eef1df_100%)] px-6 py-8 lg:px-12">
        <div class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-7xl items-center gap-10 lg:grid-cols-[1fr_620px]">
            <section class="hidden lg:block">
                <div class="max-w-xl">
                    <p class="text-sm font-bold uppercase tracking-widest text-[#806800]">Langkah 2 dari 3</p>
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
                                {{ mb_substr($dataDiri['nama_lengkap'], 0, 1) }}
                            </div>
                        </div>

                        <div class="mt-8 grid gap-5 rounded-3xl bg-white/10 p-6 text-sm">
                            <div>
                                <p class="text-slate-300">Nama Anggota</p>
                                <p class="mt-1 text-2xl font-bold">{{ $dataDiri['nama_lengkap'] }}</p>
                            </div>
                            <div>
                                <p class="text-slate-300">Nomor Kartu / NIK</p>
                                <p class="mt-1 font-mono text-xl font-bold">{{ $dataDiri['nik'] }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-slate-300">Kalangan</p>
                                    <p class="mt-1 font-semibold">Umum</p>
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

            <section class="mx-auto w-full max-w-2xl rounded-[2rem] bg-white/95 p-6 shadow-2xl shadow-slate-900/10 sm:p-10">
                <div>
                    <p class="text-sm font-bold uppercase tracking-widest text-[#806800]">Langkah 2 dari 3</p>
                    <h2 class="mt-2 text-3xl font-bold">Buat Akun Login</h2>
                    <p class="mt-2 text-slate-500">Akun ini digunakan untuk masuk dan mengakses e-kartu anggota.</p>
                </div>

                <div class="mt-8 grid gap-3 text-xs font-bold sm:grid-cols-3">
                    <div class="rounded-2xl bg-emerald-50 px-4 py-3 text-emerald-700">
                        <span class="block text-lg">01</span>
                        <span>Data Diri</span>
                    </div>
                    <div class="rounded-2xl bg-[#061b3a] px-4 py-3 text-white">
                        <span class="block text-lg">02</span>
                        <span>Akun</span>
                    </div>
                    <div class="rounded-2xl bg-[#f0f2f7] px-4 py-3 text-slate-600">
                        <span class="block text-lg text-[#061b3a]">03</span>
                        <span>E-Kartu</span>
                    </div>
                </div>

                <div class="mt-8 rounded-3xl border border-slate-100 bg-[#f8fafc] p-5">
                    <div class="flex items-start justify-between gap-5">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Ringkasan Data</p>
                            <h3 class="mt-1 font-bold">{{ $dataDiri['nama_lengkap'] }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $dataDiri['nik'] }} · {{ $dataDiri['jenis_kelamin'] }}</p>
                        </div>
                        @if ($fotoPath)
                            <img src="{{ asset('storage/'.$fotoPath) }}" alt="Foto {{ $dataDiri['nama_lengkap'] }}" class="h-16 w-16 rounded-2xl object-cover ring-2 ring-[#ffdc7c]/70">
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#061b3a] text-2xl font-bold text-white">
                                {{ mb_substr($dataDiri['nama_lengkap'], 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('register') }}" class="mt-4 inline-flex text-sm font-bold text-[#061b3a] hover:underline">
                        Ubah data diri
                    </a>
                </div>

                @if ($errors->any())
                    <div class="mt-8 rounded-2xl border border-red-200 bg-red-50 p-5 text-red-700">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-exclamation mt-0.5 text-lg"></i>
                            <div>
                                <p class="font-bold">Akun belum bisa dibuat.</p>
                                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.akun.store') }}" class="mt-8 space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                        <div class="relative">
                            <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="example@mail.com" class="w-full rounded-xl border border-slate-200 bg-[#f4f6fa] py-3 pl-11 pr-4 text-slate-700 focus:border-[#061b3a] focus:ring-4 focus:ring-[#061b3a]/10">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="password" class="mb-2 block text-sm font-bold text-slate-700">Password</label>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" class="w-full rounded-xl border border-slate-200 bg-[#f4f6fa] py-3 pl-11 pr-4 text-slate-700 focus:border-[#061b3a] focus:ring-4 focus:ring-[#061b3a]/10">
                            </div>
                            <p class="mt-2 text-xs text-slate-500">Gunakan kombinasi huruf dan angka agar akun lebih aman.</p>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-bold text-slate-700">Konfirmasi Password</label>
                            <div class="relative">
                                <i class="fa-solid fa-shield-halved absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" class="w-full rounded-xl border border-slate-200 bg-[#f4f6fa] py-3 pl-11 pr-4 text-slate-700 focus:border-[#061b3a] focus:ring-4 focus:ring-[#061b3a]/10">
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <label class="flex items-start gap-3 text-sm text-slate-600">
                            <input type="checkbox" name="terms" value="1" required @checked(old('terms')) class="mt-1 rounded border-slate-300 text-[#061b3a] focus:ring-[#061b3a]">
                            <span>Saya menyetujui <span class="font-bold text-[#061b3a]">Syarat & Ketentuan</span> layanan SIPADI dan memastikan data yang saya isi benar.</span>
                        </label>
                        <x-input-error :messages="$errors->get('terms')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full rounded-2xl bg-[#061b3a] px-6 py-4 text-lg font-semibold text-white shadow-xl shadow-[#061b3a]/20 transition hover:-translate-y-0.5 hover:bg-[#0b2a59]">
                        Buat Akun dan Terbitkan E-Kartu
                    </button>
                </form>
            </section>
        </div>
    </main>
</body>
</html>
