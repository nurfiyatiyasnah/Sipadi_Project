@extends('layouts.anggota')
@section('title', 'Dashboard - SIPADI Bukittinggi')

@push('styles')
<style>
    /* ===== Dashboard Page Styles ===== */

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
    }

    /* Hero Section */
    .hero-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
    }

    .hero-greeting {
        flex: 1;
    }

    .hero-greeting .greeting-label {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .hero-greeting .greeting-label i {
        color: #f59e0b;
    }

    .hero-greeting h1 {
        font-size: 2rem;
        font-weight: 800;
        color: #111827;
        margin: 0 0 0.5rem;
        line-height: 1.2;
    }

    .hero-greeting p {
        color: #6b7280;
        font-size: 0.9rem;
        line-height: 1.6;
        max-width: 520px;
    }

    .hero-action {
        flex-shrink: 0;
        margin-left: 2rem;
        padding-top: 1.5rem;
    }

    .btn-katalog {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--sipadi-green-dark);
        color: white;
        padding: 0.65rem 1.25rem;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(10, 61, 46, 0.25);
    }

    .btn-katalog:hover {
        background: var(--sipadi-green-light);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(10, 61, 46, 0.3);
    }

    /* Main Grid */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1.25rem;
        margin-bottom: 2.5rem;
    }

    /* Cards */
    .card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .card-header-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        font-weight: 700;
        color: #111827;
    }

    .card-header-title i {
        color: var(--sipadi-green);
        font-size: 1rem;
    }

    .card-header-link {
        font-size: 0.8rem;
        color: var(--sipadi-green);
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: color 0.2s;
    }

    .card-header-link:hover {
        color: var(--sipadi-green-light);
    }

    .card-body {
        padding: 1.25rem;
    }

    /* Borrowed Book Card */
    .book-borrowed {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }

    .book-cover-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .book-cover {
        width: 100px;
        height: 140px;
        border-radius: 0.5rem;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        background: linear-gradient(135deg, #0f4c3a 0%, #1a6b50 50%, #0a3d2e 100%);
    }

    .book-cover-placeholder {
        width: 100px;
        height: 140px;
        border-radius: 0.5rem;
        background: linear-gradient(135deg, #0f4c3a 0%, #1a6b50 50%, #0a3d2e 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,0.6);
        font-size: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .book-badge {
        position: absolute;
        top: 0.4rem;
        left: 0.4rem;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.2rem 0.5rem;
        border-radius: 0.35rem;
        white-space: nowrap;
    }

    .badge-warning {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fcd34d;
    }

    .badge-danger {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fca5a5;
    }

    .badge-success {
        background: #dcfce7;
        color: #16a34a;
        border: 1px solid #86efac;
    }

    .badge-info {
        background: #dbeafe;
        color: #2563eb;
        border: 1px solid #93c5fd;
    }

    .book-info {
        flex: 1;
        min-width: 0;
    }

    .book-info h3 {
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
        margin: 0 0 0.25rem;
        line-height: 1.3;
    }

    .book-info .book-author {
        font-size: 0.8rem;
        color: #6b7280;
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }

    .book-dates {
        display: flex;
        justify-content: space-between;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid #f3f4f6;
    }

    .book-date-item {
        font-size: 0.72rem;
        color: #9ca3af;
    }

    .book-date-item span {
        display: block;
        color: #6b7280;
        font-weight: 500;
        margin-top: 0.15rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem 1rem;
        color: #9ca3af;
    }

    .empty-state i {
        font-size: 2rem;
        margin-bottom: 0.75rem;
        display: block;
        opacity: 0.5;
    }

    .empty-state p {
        font-size: 0.85rem;
    }

    /* Right Sidebar Cards */
    .sidebar-stack {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    /* Menunggu Pengambilan */
    .pickup-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.15rem;
    }

    .pickup-card-subtitle {
        font-size: 0.8rem;
        color: #6b7280;
        margin-bottom: 0.75rem;
    }

    .pickup-location {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.75rem;
        color: #9ca3af;
        background: #f9fafb;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
    }

    .pickup-location i {
        color: var(--sipadi-green);
    }

    .pickup-badge {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        font-size: 0.65rem;
        font-weight: 600;
        padding: 0.2rem 0.5rem;
        border-radius: 0.35rem;
        background: #dbeafe;
        color: #2563eb;
        border: 1px solid #93c5fd;
    }

    .pickup-arrow {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-top: 0.5rem;
    }

    .pickup-arrow a {
        color: var(--sipadi-green);
        font-size: 0.8rem;
        text-decoration: none;
    }

    /* Pesan Baru */
    .pesan-card .card-header {
        border-bottom: 1px solid #f3f4f6;
    }

    .pesan-item {
        display: flex;
        gap: 0.75rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f9fafb;
    }

    .pesan-item:last-child {
        border-bottom: none;
    }

    .pesan-icon {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }

    .pesan-icon.warning {
        background: #fef3c7;
        color: #d97706;
    }

    .pesan-icon.info {
        background: #dbeafe;
        color: #2563eb;
    }

    .pesan-icon.success {
        background: #dcfce7;
        color: #16a34a;
    }

    .pesan-content h4 {
        font-size: 0.8rem;
        font-weight: 600;
        color: #111827;
        margin: 0 0 0.2rem;
    }

    .pesan-content p {
        font-size: 0.75rem;
        color: #6b7280;
        margin: 0;
        line-height: 1.4;
    }

    .pesan-footer {
        text-align: right;
        padding-top: 0.5rem;
    }

    .pesan-footer a {
        font-size: 0.78rem;
        color: #9ca3af;
        text-decoration: none;
        transition: color 0.2s;
    }

    .pesan-footer a:hover {
        color: var(--sipadi-green);
    }

    /* Rekomendasi Section */
    .rekomendasi-section {
        margin-bottom: 2rem;
    }

    .rekomendasi-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 1.25rem;
    }

    .rekomendasi-header h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
        margin: 0 0 0.25rem;
    }

    .rekomendasi-header p {
        font-size: 0.85rem;
        color: #6b7280;
        margin: 0;
    }

    .rekomendasi-header a {
        font-size: 0.85rem;
        color: var(--sipadi-green);
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: color 0.2s;
        white-space: nowrap;
    }

    .rekomendasi-header a:hover {
        color: var(--sipadi-green-light);
    }

    /* Book Grid */
    .book-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1.25rem;
    }

    .book-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        overflow: hidden;
        transition: all 0.25s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
    }

    .book-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
        border-color: #d1d5db;
    }

    .book-card-cover {
        position: relative;
        aspect-ratio: 3/4;
        overflow: hidden;
        background: linear-gradient(135deg, #1a3a2a, #2d5a45);
    }

    .book-card-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .book-card:hover .book-card-cover img {
        transform: scale(1.05);
    }

    .book-card-cover .cover-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,0.4);
        font-size: 2.5rem;
    }

    .book-card-status {
        position: absolute;
        top: 0.5rem;
        left: 0.5rem;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: 0.35rem;
    }

    .status-tersedia {
        background: #16a34a;
        color: white;
    }

    .status-dipinjam {
        background: #2563eb;
        color: white;
    }

    .status-tidak-tersedia {
        background: #6b7280;
        color: white;
    }

    .book-card-info {
        padding: 0.75rem;
    }

    .book-card-info h4 {
        font-size: 0.8rem;
        font-weight: 600;
        color: #111827;
        margin: 0 0 0.2rem;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .book-card-info .book-card-author {
        font-size: 0.72rem;
        color: #9ca3af;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .book-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .hero-section {
            flex-direction: column;
        }

        .hero-action {
            margin-left: 0;
            padding-top: 1rem;
        }
    }

    @media (max-width: 640px) {
        .dashboard-container {
            padding: 1rem;
        }

        .hero-greeting h1 {
            font-size: 1.5rem;
        }

        .book-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .book-borrowed {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .book-dates {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    {{-- Hero Section --}}
    <section class="hero-section">
        <div class="hero-greeting">
            <div class="greeting-label">
                <i class="fa-solid fa-sun"></i>
                {{ $sapaan }}
            </div>
            <h1>Halo, {{ $namaUser }}</h1>
            <p>Selamat datang kembali di layanan perpustakaan digital Anda. Berikut adalah ringkasan aktivitas dan rekomendasi terbaru untuk Anda hari ini.</p>
        </div>
        <div class="hero-action">
            <a href="#" class="btn-katalog">
                <i class="fa-solid fa-compass"></i>
                Jelajah Katalog Baru
            </a>
        </div>
    </section>

    {{-- Main Grid: Borrowed + Sidebar --}}
    <div class="dashboard-grid">
        {{-- Left: Buku Sedang Dipinjam --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-title">
                    <i class="fa-solid fa-book-open"></i>
                    Buku Sedang Dipinjam
                </div>
                <a href="#" class="card-header-link">
                    Lihat Riwayat <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <div class="card-body">
                @if($bukuDipinjam->count() > 0)
                    @php $buku = $bukuDipinjam->first(); @endphp
                    <div class="book-borrowed">
                        <div class="book-cover-wrapper">
                            @if($buku->gambar_cover)
                                <img src="{{ asset('storage/' . $buku->gambar_cover) }}" alt="{{ $buku->judul }}" class="book-cover">
                            @else
                                <div class="book-cover-placeholder">
                                    <i class="fa-solid fa-book"></i>
                                </div>
                            @endif
                            @if($buku->sisa_hari !== null)
                                <span class="book-badge {{ $buku->sisa_hari <= 0 ? 'badge-danger' : ($buku->sisa_hari <= 3 ? 'badge-warning' : 'badge-success') }}">
                                    @if($buku->sisa_hari <= 0)
                                        Terlambat {{ abs($buku->sisa_hari) }} Hari
                                    @else
                                        Sisa {{ $buku->sisa_hari }} Hari
                                    @endif
                                </span>
                            @endif
                        </div>
                        <div class="book-info">
                            <h3>{{ $buku->judul }}</h3>
                            <p class="book-author">{{ $buku->penulis }}</p>

                            <div class="book-dates">
                                <div class="book-date-item">
                                    Dipinjam:
                                    <span>{{ $buku->tanggal_dipinjam ? $buku->tanggal_dipinjam->translatedFormat('d F Y') : '-' }}</span>
                                </div>
                                <div class="book-date-item">
                                    Jatuh Tempo:
                                    <span>{{ $buku->tanggal_jatuh_tempo ? $buku->tanggal_jatuh_tempo->translatedFormat('d F Y') : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fa-regular fa-face-smile"></i>
                        <p>Anda tidak memiliki buku yang sedang dipinjam.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="sidebar-stack">
            {{-- Menunggu Pengambilan --}}
            <div class="card" style="position: relative;">
                @if($menungguPengambilan->count() > 0)
                    <span class="pickup-badge">{{ $menungguPengambilan->count() }} Antrian</span>
                @endif
                <div class="card-body">
                    <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                        <div style="width: 36px; height: 36px; border-radius: 0.5rem; background: #f0fdf4; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fa-regular fa-clipboard" style="color: var(--sipadi-green); font-size: 1rem;"></i>
                        </div>
                        <div style="flex: 1;">
                            <h3 class="pickup-card-title">Menunggu Pengambilan</h3>
                            @if($menungguPengambilan->count() > 0)
                                @php $pickup = $menungguPengambilan->first(); @endphp
                                <p class="pickup-card-subtitle">{{ $pickup->judul }}</p>
                                <div class="pickup-location">
                                    <i class="fa-solid fa-location-dot"></i>
                                    {{ $pickup->lokasi }}
                                </div>
                            @else
                                <p class="pickup-card-subtitle" style="color: #9ca3af;">Tidak ada buku yang menunggu diambil.</p>
                            @endif
                        </div>
                    </div>
                    @if($menungguPengambilan->count() > 0)
                        <div class="pickup-arrow">
                            <a href="#">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Pesan Baru --}}
            <div class="card pesan-card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="fa-regular fa-bell"></i>
                        Pesan Baru
                    </div>
                </div>
                <div class="card-body" style="padding-top: 0.5rem; padding-bottom: 0.75rem;">
                    @if($pesanBaru->count() > 0)
                        @foreach($pesanBaru->take(3) as $pesan)
                            <div class="pesan-item">
                                <div class="pesan-icon {{ $pesan->jenis_notifikasi === 'Peringatan' ? 'warning' : ($pesan->jenis_notifikasi === 'Info' ? 'info' : 'success') }}">
                                    <i class="fa-solid {{ $pesan->jenis_notifikasi === 'Peringatan' ? 'fa-exclamation' : ($pesan->jenis_notifikasi === 'Info' ? 'fa-info' : 'fa-check') }}"></i>
                                </div>
                                <div class="pesan-content">
                                    <h4>{{ $pesan->judul }}</h4>
                                    <p>{{ Str::limit($pesan->isi, 80) }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="pesan-item" style="justify-content: center; border: none;">
                            <p style="font-size: 0.8rem; color: #9ca3af;">Tidak ada pesan baru.</p>
                        </div>
                    @endif
                    <div class="pesan-footer">
                        <a href="#">Tandai semua dibaca</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Rekomendasi Untuk Anda --}}
    <section class="rekomendasi-section">
        <div class="rekomendasi-header">
            <div>
                <h2>Rekomendasi Untuk Anda</h2>
                <p>Berdasarkan riwayat peminjaman dan minat baca Anda.</p>
            </div>
            <a href="#">
                Lihat Semua <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="book-grid">
            @forelse($rekomendasi as $rekom)
                <div class="book-card">
                    <div class="book-card-cover">
                        @if($rekom->gambar_cover)
                            <img src="{{ asset('storage/' . $rekom->gambar_cover) }}" alt="{{ $rekom->judul }}">
                        @else
                            <div class="cover-placeholder">
                                <i class="fa-solid fa-book"></i>
                            </div>
                        @endif
                        <span class="book-card-status {{ $rekom->status === 'Tersedia' ? 'status-tersedia' : ($rekom->status === 'Dipinjam' ? 'status-dipinjam' : 'status-tidak-tersedia') }}">
                            {{ $rekom->status }}
                        </span>
                    </div>
                    <div class="book-card-info">
                        <h4>{{ $rekom->judul }}</h4>
                        <p class="book-card-author">{{ $rekom->penulis }}</p>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1;">
                    <div class="empty-state">
                        <i class="fa-solid fa-book-open-reader"></i>
                        <p>Belum ada rekomendasi buku untuk Anda.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
