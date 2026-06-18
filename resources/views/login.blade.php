<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login ke Arsip Digital Bukittinggi - Gerbang Warisan Sejarah Kota Wisata">
    <title>Login - Arsip Digital Bukittinggi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }

        body {
            background: linear-gradient(135deg, #e8eaf6 0%, #c5cae9 25%, #e3e0f3 50%, #f5f5fa 75%, #e8eaf6 100%);
            min-height: 100vh;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow:
                0 8px 32px rgba(100, 100, 180, 0.1),
                0 2px 8px rgba(100, 100, 180, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .input-field {
            background: #f8f9fc;
            border: 1.5px solid #e8eaef;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-field:focus {
            border-color: #9fa8da;
            box-shadow: 0 0 0 3px rgba(159, 168, 218, 0.15);
            background: #ffffff;
        }

        .btn-login {
            background: #1a2332;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: 0.12em;
        }

        .btn-login:hover {
            background: #0f1724;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26, 35, 50, 0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-google {
            border: 1.5px solid #e8eaef;
            transition: all 0.3s ease;
        }

        .btn-google:hover {
            border-color: #c5cae9;
            background: #f8f9fc;
            box-shadow: 0 2px 8px rgba(100, 100, 180, 0.08);
        }

        .checkbox-custom {
            accent-color: #1a2332;
        }

        .link-hover {
            transition: color 0.2s ease;
        }

        .link-hover:hover {
            color: #3949ab;
        }

        /* Subtle floating animation for logos */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }

        .logo-float {
            animation: float 4s ease-in-out infinite;
        }

        .logo-float:nth-child(2) {
            animation-delay: 0.5s;
        }

        /* Error message styling */
        .error-message {
            color: #e53935;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body class="flex items-center justify-center py-8 px-4">
    <main class="w-full max-w-xl">
        <!-- Header: Logos & Title -->
        <div class="flex flex-col items-center mb-7">
            <div class="flex gap-3 items-center mb-4">
                <img src="{{ asset('images/logo-kota.png') }}" alt="Logo Kota Bukittinggi" class="w-14 h-14 object-contain logo-float" id="logo-kota">
                <img src="{{ asset('images/logo-dinas.png') }}" alt="Logo Dinas Perpustakaan" class="w-14 h-14 object-contain logo-float" id="logo-dinas">
            </div>
            <h1 class="text-2xl font-semibold text-[#1a2332] tracking-tight">Arsip Digital Bukittinggi</h1>
            <p class="text-sm text-slate-500 mt-1">Gerbang Warisan Sejarah Kota Wisata</p>
        </div>

        <!-- Login Card -->
        <section class="login-card rounded-3xl p-8 sm:p-10 mx-auto max-w-md" id="login-card">
            <h2 class="text-center text-lg font-semibold text-[#1a2332] mb-7">Masuk ke Akun</h2>

            {{-- Session Error --}}
            @if(session('status'))
                <div class="mb-4 p-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm text-center">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm text-center">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5" id="login-form">
                @csrf

                <!-- Email / Username -->
                <div>
                    <label for="email" class="block text-xs font-medium text-slate-600 mb-2">Email atau Username</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            id="email"
                            name="email"
                            placeholder="Masukkan identitas Anda"
                            value="{{ old('email') }}"
                            required
                            autocomplete="username"
                            class="input-field w-full pl-12 pr-4 py-3.5 rounded-xl text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-medium text-slate-600 mb-2">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan kata sandi Anda"
                            required
                            autocomplete="current-password"
                            class="input-field w-full pl-12 pr-12 py-3.5 rounded-xl text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
                        >
                        <button
                            type="button"
                            id="toggle-password"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
                            onclick="togglePassword()"
                            aria-label="Toggle password visibility"
                        >
                            <!-- Eye Open -->
                            <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <!-- Eye Closed (hidden by default) -->
                            <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer" for="remember">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded checkbox-custom cursor-pointer">
                        <span>Ingat Saya</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#1a2332] link-hover" id="forgot-password-link">Lupa Sandi?</a>
                </div>

                <!-- Login Button -->
                <div>
                    <button type="submit" id="btn-login" class="btn-login w-full text-white py-3.5 rounded-full font-medium text-sm uppercase tracking-widest shadow-lg">
                        Login ke Arsip
                    </button>
                </div>

                <!-- Divider -->
                <div class="flex items-center gap-4 my-1">
                    <div class="flex-1 h-px bg-slate-200"></div>
                    <span class="text-xs text-slate-400 whitespace-nowrap">Atau masuk dengan</span>
                    <div class="flex-1 h-px bg-slate-200"></div>
                </div>

                <!-- Google Login -->
                <div>
                    <a href="/auth/google" id="btn-google" class="btn-google flex items-center gap-3 justify-center w-full rounded-full py-3.5 bg-white">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        <span class="text-sm text-slate-700 font-medium">Masuk dengan Google</span>
                    </a>
                </div>

                <!-- Register Link -->
                <p class="text-center text-sm text-slate-500 pt-1">
                    Belum punya akses?
                    <a href="{{ route('register') }}" class="text-[#1a2332] font-semibold link-hover" id="register-link">Ajukan Akun Anggota</a>
                </p>
            </form>
        </section>

        <!-- Footer -->
        <footer class="mt-8 text-center" id="footer">
            <p class="text-xs text-slate-400">© 2024 Dinas Perpustakaan dan Kearsipan Kota Bukittinggi</p>
            <div class="mt-2.5 flex justify-center gap-5">
                <a href="#" class="text-xs text-slate-400 link-hover hover:text-slate-600">Panduan</a>
                <a href="#" class="text-xs text-slate-400 link-hover hover:text-slate-600">Kebijakan Privasi</a>
                <a href="#" class="text-xs text-slate-400 link-hover hover:text-slate-600">Hubungi Kami</a>
            </div>
        </footer>
    </main>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>