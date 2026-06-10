<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Digital Archive</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-56 bg-gradient-to-b from-gray-900 to-gray-800 text-white flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-700">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-yellow-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-archive text-gray-900 font-bold"></i>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold">Digital Archive</h1>
                        <p class="text-xs text-gray-400">Bukittinggi</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition 
                   {{ request()->routeIs('admin.dashboard') ? 'bg-yellow-400 text-gray-900' : 'text-gray-300 hover:bg-gray-700' }}">
                    <i class="fas fa-th-large w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.dashboard.koleksi') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-book-open w-5"></i>
                    <span>Buku</span>
                </a>
                <a href="" class="flex items-center gap-3 px-4 py-3 rounded-lg transition text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-user w-5"></i>
                    <span>Anggota</span>
                </a>
                <a href="" class="flex items-center gap-3 px-4 py-3 rounded-lg transition text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-calendar w-5"></i>
                    <span>Agenda</span>
                </a>
                <a href="" class="flex items-center gap-3 px-4 py-3 rounded-lg transition text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-newspaper w-5"></i>
                    <span>Berita</span>
                </a>
                <a href="" class="flex items-center gap-3 px-4 py-3 rounded-lg transition text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-comment-dots w-5"></i>
                    <span>Aduan</span>
                </a>
                <a href="" class="flex items-center gap-3 px-4 py-3 rounded-lg transition text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-book-reader w-5"></i>
                    <span>Peminjaman</span>
                </a>
            

            <!-- Footer Sidebar -->
            <div class="px-3 py-4 border-t border-gray-700 space-y-2">
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-700 transition">
                    <i class="fas fa-cog w-5"></i>
                    <span>Settings</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-700 transition">
                    <i class="fas fa-circle-question w-5"></i>
                    <span>Support</span>
                </a>
            </div>

            <!-- Logout -->
            <div class="p-3 border-t border-gray-700">
                <button type="button" onclick="document.getElementById('logout-form').submit()" 
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-red-600 hover:text-white transition">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Logout</span>
                </button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-8 py-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Selamat Pagi, Administrator</h2>
                        <p class="text-sm text-gray-500">Kelola khazanah literasi Kota Bukittinggi hari ini.</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-bell text-gray-600 text-lg"></i>
                        </button>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-redo text-gray-600 text-lg"></i>
                        </button>
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-600"></i>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="flex-1 overflow-auto p-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
