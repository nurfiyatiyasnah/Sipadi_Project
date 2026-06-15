<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPADI - Landing Page</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #222;
        }

        .navbar {
            background: #1f2937;
            color: white;
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 16px;
        }

        .hero {
            padding: 80px 40px;
            text-align: center;
            background: white;
        }

        .hero h1 {
            font-size: 42px;
            margin-bottom: 12px;
        }

        .hero p {
            font-size: 18px;
            color: #555;
            margin-bottom: 28px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 4px;
        }

        .btn-secondary {
            background: #4b5563;
        }

        .section {
            padding: 40px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 24px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .footer {
            text-align: center;
            padding: 20px;
            background: #1f2937;
            color: white;
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div>
            <strong>SIPADI</strong>
        </div>

        <div>
            @auth
                @if (Auth::user()->isPetugas())
                    <a href="{{ route('petugas.dashboard') }}">Dashboard Petugas</a>
                @else
                    <a href="{{ route('anggota.e-kartu') }}">E-Kartu</a>
                @endif
                <a href="{{ route('profile.edit') }}">Profil</a>

                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:none;color:white;cursor:pointer;margin-left:16px;">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Daftar</a>
            @endauth
        </div>
    </div>

    <div class="hero">
        <h1>Selamat Datang di SIPADI</h1>
        <p>Sistem Informasi Perpustakaan dan Arsip Digital.</p>

        @auth
            @if (Auth::user()->isPetugas())
                <a href="{{ route('petugas.dashboard') }}" class="btn">Masuk Dashboard Petugas</a>
            @else
                <a href="{{ route('anggota.e-kartu') }}" class="btn">Lihat E-Kartu</a>
                <a href="{{ route('profile.edit') }}" class="btn btn-secondary">Lihat Profil</a>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn">Login</a>
            <a href="{{ route('register') }}" class="btn btn-secondary">Daftar Anggota</a>
        @endauth
    </div>

    <div class="section">
        <h2>Fitur Utama</h2>

        <div class="cards">
            <div class="card">
                <h3>Katalog Buku</h3>
                <p>Pengguna dapat melihat daftar koleksi buku yang tersedia di perpustakaan.</p>
            </div>

            <div class="card">
                <h3>Peminjaman Buku</h3>
                <p>Anggota dapat mengajukan peminjaman buku melalui sistem.</p>
            </div>

            <div class="card">
                <h3>Riwayat Peminjaman</h3>
                <p>Anggota dapat melihat riwayat peminjaman dan status pengembalian buku.</p>
            </div>

            <div class="card">
                <h3>E-Kartu Anggota</h3>
                <p>Anggota dapat mengakses kartu anggota digital.</p>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} SIPADI - Sistem Informasi Perpustakaan dan Arsip Digital
    </div>

</body>
</html>
