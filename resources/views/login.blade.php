<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Arsip Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-slate-200 to-white flex items-center justify-center py-12">
    <main class="w-full max-w-3xl px-6">
        <div class="flex flex-col items-center mb-8">
            <div class="flex gap-4 items-center">
                <img src="/images/logo-kota.png" alt="logo-kota" class="w-16 h-16 object-contain">
                <img src="/images/logo-dinas.png" alt="logo-dinas" class="w-16 h-16 object-contain">
            </div>
            <h1 class="mt-4 text-2xl font-semibold text-slate-800">Arsip Digital Bukittinggi</h1>
            <p class="text-sm text-slate-500">Gerbang Warisan Sejarah Kota Wisata</p>
        </div>

        <section class="bg-white/95 backdrop-blur-sm shadow-2xl rounded-3xl p-8 md:p-12 mx-auto max-w-lg">
            <h2 class="text-center text-lg font-semibold text-slate-800 mb-6">Masuk ke Akun</h2>

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs text-slate-600 mb-2">Email atau Username</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0z" />
                            </svg>
                        </span>
                        <input type="text" name="email" placeholder="Masukkan identitas Anda" value="{{ old('email') }}"
                            class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-300">
                    </div>
                </div>

                <div>
                    <label class="block text-xs text-slate-600 mb-2">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 11V8a5 5 0 00-10 0v3" />
                            </svg>
                        </span>
                        <input type="password" name="password" placeholder="Masukkan kata sandi Anda"
                            class="w-full pl-12 pr-12 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-300">

                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.644C3.414 8.65 7.378 5.625 12 5.625c4.622 0 8.586 3.025 9.964 6.053a1.012 1.012 0 010 .644C20.586 15.35 16.622 18.375 12 18.375c-4.622 0-8.586-3.025-9.964-6.053z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-600">
                        <input type="checkbox" name="remember" class="w-4 h-4">
                        Ingat Saya
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-slate-700 font-medium">Lupa Sandi?</a>
                </div>

                <div>
                    <button type="submit" class="w-full bg-slate-900 text-white py-3 rounded-full shadow-lg uppercase tracking-wider">Login ke Arsip</button>
                </div>

                <div class="flex items-center my-2">
                    <div class="flex-1 h-px bg-slate-200"></div>
                    <div class="px-4 text-xs text-slate-400">Atau masuk dengan</div>
                    <div class="flex-1 h-px bg-slate-200"></div>
                </div>

                <div>
                    <a href="/auth/google" class="flex items-center gap-3 justify-center w-full border border-slate-200 rounded-full py-3 bg-white hover:bg-slate-50">
                        <img src="/images/google-icon.png" alt="google" class="w-5 h-5">
                        <span class="text-sm text-slate-700">Masuk dengan Google</span>
                    </a>
                </div>

                <p class="text-center text-sm text-slate-500">Belum punya akses? <a href="{{ route('register') }}" class="text-slate-700 font-medium">Ajukan Akun Anggota</a></p>
            </form>
        </section>

        <footer class="mt-8 text-center text-xs text-slate-400">
            <div>© 2024 Dinas Perpustakaan dan Kearsipan Kota Bukittinggi</div>
            <div class="mt-2 flex justify-center gap-4">
                <a href="#" class="hover:underline">Panduan</a>
                <a href="#" class="hover:underline">Kebijakan Privasi</a>
                <a href="#" class="hover:underline">Hubungi Kami</a>
            </div>
        </footer>
    </main>

</body>
</html>