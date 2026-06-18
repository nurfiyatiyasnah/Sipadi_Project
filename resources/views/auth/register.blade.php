<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Data Diri - Registrasi SIPADI</title>

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
                    <div class="mb-10 inline-flex items-center gap-3 rounded-full border border-[#061b3a]/10 bg-white/70 px-4 py-2 text-sm font-bold shadow-sm">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#ffdc7c]">
                            <i class="fa-solid fa-building-columns"></i>
                        </span>
                        SIPADI Bukittinggi
                    </div>

                    <h1 class="font-serif text-6xl font-bold leading-[1.05]">
                        Preservasi Digital,<br>
                        <span class="text-[#8ea2d4]">Warisan Abadi.</span>
                    </h1>

                    <p class="mt-8 max-w-lg text-xl leading-9 text-slate-600">
                        Mulai dari data diri, lanjutkan membuat akun, lalu e-kartu digital anggota aktif otomatis.
                    </p>

                    <div class="mt-8 grid max-w-lg grid-cols-3 gap-3">
                        <div class="rounded-2xl border border-white bg-white/70 p-4 shadow-sm">
                            <p class="text-2xl font-bold">01</p>
                            <p class="mt-1 text-sm text-slate-500">Data diri</p>
                        </div>
                        <div class="rounded-2xl border border-white bg-white/70 p-4 shadow-sm">
                            <p class="text-2xl font-bold">02</p>
                            <p class="mt-1 text-sm text-slate-500">Akun login</p>
                        </div>
                        <div class="rounded-2xl border border-white bg-white/70 p-4 shadow-sm">
                            <p class="text-2xl font-bold">03</p>
                            <p class="mt-1 text-sm text-slate-500">E-Kartu</p>
                        </div>
                    </div>

                    <div class="mt-10 overflow-hidden rounded-[2rem] bg-[#061b3a] shadow-2xl">
                        <div class="relative h-[420px] bg-cover bg-center" style="background-image: linear-gradient(180deg, rgba(6,27,58,.10), rgba(6,27,58,.92)), url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1200&q=80')">
                            <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                                <span class="rounded-full bg-[#ffdc7c] px-4 py-2 text-xs font-bold uppercase tracking-widest text-[#061b3a]">Langkah 1</span>
                                <h2 class="mt-5 text-2xl font-bold">Lengkapi Identitas Anggota</h2>
                                <p class="mt-1 text-slate-200">Data ini dipakai untuk menerbitkan e-kartu digital Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mx-auto w-full max-w-2xl rounded-[2rem] bg-white/95 p-6 shadow-2xl shadow-slate-900/10 sm:p-10">
                <div>
                    <p class="text-sm font-bold uppercase tracking-widest text-[#806800]">Langkah 1 dari 3</p>
                    <h2 class="mt-2 text-3xl font-bold">Isi Data Diri</h2>
                    <p class="mt-2 text-slate-500">Masukkan identitas sesuai KTP untuk pembuatan akun anggota.</p>
                </div>

                <div class="mt-8 grid gap-3 text-xs font-bold sm:grid-cols-3">
                    <div class="rounded-2xl bg-[#061b3a] px-4 py-3 text-white">
                        <span class="block text-lg">01</span>
                        <span>Data Diri</span>
                    </div>
                    <div class="rounded-2xl bg-[#f0f2f7] px-4 py-3 text-slate-600">
                        <span class="block text-lg text-[#061b3a]">02</span>
                        <span>Akun</span>
                    </div>
                    <div class="rounded-2xl bg-[#f0f2f7] px-4 py-3 text-slate-600">
                        <span class="block text-lg text-[#061b3a]">03</span>
                        <span>E-Kartu</span>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mt-8 rounded-2xl border border-red-200 bg-red-50 p-5 text-red-700">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-exclamation mt-0.5 text-lg"></i>
                            <div>
                                <p class="font-bold">Data diri belum bisa dilanjutkan.</p>
                                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.data.store') }}" enctype="multipart/form-data" class="mt-8 space-y-6" x-data="{ fileName: '' }">
                    @csrf

                    <div>
                        <label for="nama_lengkap" class="mb-2 block text-sm font-bold text-slate-700">Nama Lengkap</label>
                        <div class="relative">
                            <i class="fa-regular fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap', session('registration.data_diri.nama_lengkap')) }}" required autofocus autocomplete="name" placeholder="Masukkan nama sesuai KTP" class="w-full rounded-xl border border-slate-200 bg-[#f4f6fa] py-3 pl-11 pr-4 text-slate-700 focus:border-[#061b3a] focus:ring-4 focus:ring-[#061b3a]/10">
                        </div>
                        <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
                    </div>

                    <div>
                        <label for="nik" class="mb-2 block text-sm font-bold text-slate-700">NIK</label>
                        <div class="relative">
                            <i class="fa-regular fa-address-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="nik" type="text" name="nik" value="{{ old('nik', session('registration.data_diri.nik')) }}" required inputmode="numeric" maxlength="16" pattern="[0-9]{16}" placeholder="Masukkan 16 digit NIK sesuai KTP" class="w-full rounded-xl border border-slate-200 bg-[#f4f6fa] py-3 pl-11 pr-4 text-slate-700 focus:border-[#061b3a] focus:ring-4 focus:ring-[#061b3a]/10">
                        </div>
                        <p class="mt-2 text-xs text-slate-500">Gunakan 16 digit angka dan pastikan NIK belum pernah terdaftar.</p>
                        <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="jenis_kelamin" class="mb-2 block text-sm font-bold text-slate-700">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" required class="w-full rounded-xl border border-slate-200 bg-[#f4f6fa] py-3 text-slate-700 focus:border-[#061b3a] focus:ring-4 focus:ring-[#061b3a]/10">
                                <option value="">Pilih jenis kelamin</option>
                                <option value="Laki-laki" @selected(old('jenis_kelamin', session('registration.data_diri.jenis_kelamin')) === 'Laki-laki')>Laki-laki</option>
                                <option value="Perempuan" @selected(old('jenis_kelamin', session('registration.data_diri.jenis_kelamin')) === 'Perempuan')>Perempuan</option>
                            </select>
                            <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="mb-2 block text-sm font-bold text-slate-700">Tanggal Lahir</label>
                            <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', session('registration.data_diri.tanggal_lahir')) }}" max="{{ now()->subDay()->toDateString() }}" required class="w-full rounded-xl border border-slate-200 bg-[#f4f6fa] py-3 text-slate-700 focus:border-[#061b3a] focus:ring-4 focus:ring-[#061b3a]/10">
                            <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <label for="alamat" class="mb-2 block text-sm font-bold text-slate-700">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3" required placeholder="Masukkan alamat tempat tinggal" class="w-full rounded-xl border border-slate-200 bg-[#f4f6fa] px-4 py-3 text-slate-700 focus:border-[#061b3a] focus:ring-4 focus:ring-[#061b3a]/10">{{ old('alamat', session('registration.data_diri.alamat')) }}</textarea>
                        <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                    </div>

                    <div>
                        <label for="foto" class="mb-2 block text-sm font-bold text-slate-700">Foto Profil <span class="font-medium text-slate-400">(Opsional)</span></label>
                        <label class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 px-6 py-8 text-center transition hover:border-[#061b3a] hover:bg-[#061b3a]/5">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-500"></i>
                            <span class="mt-3 font-semibold text-slate-700" x-text="fileName || '{{ session('registration.foto_path') ? 'Foto sudah dipilih' : 'Unggah foto profil' }}'"></span>
                            <span class="mt-1 text-sm text-slate-500">JPG/PNG maksimal 2MB</span>
                            <input id="foto" name="foto" type="file" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="sr-only" @change="fileName = $event.target.files[0]?.name || ''">
                        </label>
                        <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full rounded-2xl bg-[#061b3a] px-6 py-4 text-lg font-semibold text-white shadow-xl shadow-[#061b3a]/20 transition hover:-translate-y-0.5 hover:bg-[#0b2a59]">
                        Lanjut Buat Akun
                    </button>

                    <p class="text-center text-slate-600">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-bold text-[#061b3a] hover:underline">Login</a>
                    </p>
                </form>
            </section>
        </div>
    </main>
</body>
</html>
