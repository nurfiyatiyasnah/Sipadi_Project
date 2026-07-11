@extends('layouts.public')
@section('title', 'Peminjaman Saya - SIPADI Bukittinggi')
@section('content')
<script>
    window.autoOpenTicket = @json($autoOpenTicket ?? null);
</script>

<div class="max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-12 py-12" x-data="{ activeTicket: window.autoOpenTicket }">
    <!-- Header Block -->
    <div class="border-b border-slate-200/60 pb-5 mb-8">
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#04241e] tracking-tight flex flex-wrap items-center gap-3">
            <i class="fa-solid fa-book-bookmark text-[#04241e] shrink-0"></i>
            Peminjaman Saya
        </h1>
        <p class="text-sm text-slate-500 mt-2">Pantau status pengajuan, jadwal pengambilan, dan riwayat peminjaman buku Anda.</p>
    </div>

    <!-- Alert Success / Error -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-250 text-emerald-800 text-sm font-semibold flex items-start gap-3">
            <i class="fa-solid fa-circle-check text-lg shrink-0 mt-0.5"></i>
            <span class="min-w-0 break-words">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-250 text-rose-800 text-sm font-semibold flex items-start gap-3">
            <i class="fa-solid fa-circle-exclamation text-lg shrink-0 mt-0.5"></i>
            <span class="min-w-0 break-words">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Borrowing List -->
    <div class="min-w-0 bg-white rounded-3xl border border-slate-200/80 shadow-md shadow-[#04241e]/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#04241e]/5 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Buku</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Kode Peminjaman</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal Pengajuan</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Jatuh Tempo</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">Aksi / Info</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($peminjamans as $p)
                        @php
                            $detail = $p->detailPeminjaman->first();
                            $buku = $detail?->buku;
                            $status = strtolower($p->status_peminjaman);
                            
                            // Badge color mapping
                            $badgeClass = 'bg-slate-50 text-slate-600 border-slate-200';
                            $statusLabel = 'Unknown';
                            if ($status === 'diajukan') {
                                $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                $statusLabel = 'Diajukan';
                            } elseif ($status === 'siap_diambil') {
                                $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                $statusLabel = 'Siap Diambil';
                            } elseif ($status === 'aktif') {
                                $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                $statusLabel = 'Aktif (Dipinjam)';
                            } elseif ($status === 'selesai') {
                                $badgeClass = 'bg-slate-100 text-slate-700 border-slate-300';
                                $statusLabel = 'Selesai';
                            } elseif ($status === 'terlambat') {
                                $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                $statusLabel = 'Terlambat';
                            } elseif ($status === 'ditolak') {
                                $badgeClass = 'bg-rose-100 text-rose-800 border-rose-300';
                                $statusLabel = 'Ditolak';
                            } elseif ($status === 'dibatalkan') {
                                $badgeClass = 'bg-slate-200 text-slate-800 border-slate-400';
                                $statusLabel = 'Dibatalkan';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <!-- Buku Cover & Info -->
                            <td class="px-6 py-4 whitespace-nowrap sm:whitespace-normal">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-16 bg-slate-100 rounded-lg overflow-hidden shrink-0 border border-slate-200/60 shadow-sm">
                                        @if($buku && $buku->gambar_cover)
                                            @php
                                                $imageUrl = str_starts_with($buku->gambar_cover, 'http') ? $buku->gambar_cover : asset('storage/' . $buku->gambar_cover);
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="{{ $buku->judul }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-[#04241e] to-[#0f4c3a] p-1 text-white flex flex-col justify-center text-center">
                                                <i class="fa-solid fa-book text-sm"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="leading-tight">
                                        <span class="block text-sm font-bold text-slate-800 line-clamp-2 max-w-[240px] break-words">{{ $buku->judul ?? 'Buku' }}</span>
                                        <span class="block text-[11px] text-slate-400 mt-1 break-words">{{ $buku->penulis ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Kode Peminjaman -->
                            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-700 whitespace-nowrap">
                                #{{ $p->kode_peminjaman }}
                            </td>

                            <!-- Tanggal Pengajuan -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-slate-500">
                                {{ $p->tanggal_pengajuan ? $p->tanggal_pengajuan->translatedFormat('d M Y, H:i') : ($p->created_at ? $p->created_at->translatedFormat('d M Y, H:i') : '-') }} WIB
                            </td>

                            <!-- Tanggal Jatuh Tempo -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-600">
                                @if($p->tanggal_jatuh_tempo)
                                    {{ \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->translatedFormat('d M Y') }}
                                @else
                                    <span class="text-slate-400 font-semibold">-</span>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold capitalize border {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                @if($status === 'siap_diambil')
                                    <!-- Tiket Pengambilan Button -->
                                    <button 
                                        @click="activeTicket = {
                                            kode: '{{ $p->kode_peminjaman }}',
                                            judul: '{{ addslashes($buku->judul ?? 'Buku') }}',
                                            penulis: '{{ addslashes($buku->penulis ?? '-') }}',
                                            tanggal: '{{ \Carbon\Carbon::parse($p->jadwalPengambilan?->tanggal_pengambilan)->translatedFormat('d F Y') }}',
                                            waktu: '{{ date('H:i', strtotime($p->jadwalPengambilan?->jam_mulai)) . ' - ' . date('H:i', strtotime($p->jadwalPengambilan?->jam_selesai)) . ' WIB' }}',
                                            lokasi: '{{ addslashes($p->jadwalPengambilan?->lokasi_pengambilan ?? 'Meja Sirkulasi') }}',
                                            pesan: '{{ addslashes($p->jadwalPengambilan?->pesan ?? 'Harap tunjukkan tiket ini ke petugas.') }}'
                                        }"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#04241e] hover:bg-[#06342c] text-white text-xs font-bold rounded-lg shadow-sm transition"
                                    >
                                        <i class="fa-solid fa-ticket"></i> Tiket Pengambilan
                                    </button>
                                @elseif($status === 'aktif' || $status === 'terlambat')
                                    <div class="text-[10px] font-semibold text-slate-400 text-left">
                                        <span class="block"><i class="fa-regular fa-calendar-check mr-1 text-slate-400"></i>Diambil: {{ \Carbon\Carbon::parse($p->tanggal_diambil)->translatedFormat('d F Y') }}</span>
                                    </div>
                                @elseif($status === 'diajukan')
                                    <span class="text-xs text-amber-600 font-semibold"><i class="fa-regular fa-clock mr-1"></i>Menunggu Petugas</span>
                                @elseif($status === 'ditolak')
                                    <span class="text-xs text-rose-500 font-semibold" title="{{ $p->catatan_admin }}"><i class="fa-regular fa-comment-dots mr-1"></i>Ditolak Petugas</span>
                                @elseif($status === 'dibatalkan')
                                    <span class="text-xs text-slate-500 font-semibold" title="{{ $p->catatan_admin }}"><i class="fa-regular fa-circle-xmark mr-1"></i>Dibatalkan Petugas</span>
                                @else
                                    <span class="text-xs text-slate-400 font-semibold">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-400">
                                <i class="fa-regular fa-folder-open text-3xl mb-3 block opacity-50"></i>
                                Anda belum memiliki riwayat peminjaman buku.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($peminjamans->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs text-slate-500">
                    Menampilkan {{ $peminjamans->firstItem() }}-{{ $peminjamans->lastItem() }} dari {{ $peminjamans->total() }} entri
                </p>
                <div>
                    {{ $peminjamans->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Ticket Modal (AlpineJS based) -->
    <div 
        class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-show="activeTicket" 
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            class="bg-white rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl border border-slate-100"
            @click.outside="activeTicket = null"
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <!-- Header Ticket -->
            <div class="bg-[#04241e] text-white p-6 text-center relative">
                <button @click="activeTicket = null" class="absolute right-4 top-4 text-white/70 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
                <div class="mx-auto w-12 h-12 bg-white/10 rounded-full flex items-center justify-center mb-3">
                    <i class="fa-solid fa-qrcode text-xl text-white"></i>
                </div>
                <h3 class="font-extrabold text-lg tracking-tight">TIKET PENGAMBILAN</h3>
                <p class="text-[10px] text-[#ffdc7c] mt-1 uppercase tracking-wider font-semibold">Tunjukkan tiket ini kepada petugas perpustakaan</p>
            </div>

            <!-- Body Ticket -->
            <div class="p-6 space-y-5 text-slate-700 bg-[#fafafa]">
                <div>
                    <span class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-widest">KODE TRANSAKSI</span>
                    <span class="block break-all font-mono font-extrabold text-slate-800 text-sm mt-0.5" x-text="'#' + activeTicket?.kode"></span>
                </div>

                <div>
                    <span class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-widest">BUKU YANG DIPINJAM</span>
                    <span class="block break-words font-bold text-slate-800 text-xs mt-0.5 line-clamp-1" x-text="activeTicket?.judul"></span>
                    <span class="block break-words text-[10px] text-slate-400 mt-0.5" x-text="activeTicket?.penulis"></span>
                </div>

                <div class="grid grid-cols-2 gap-4 border-t border-b border-dashed border-slate-200 py-4 my-2">
                    <div>
                        <span class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-widest">TANGGAL</span>
                        <span class="block font-bold text-slate-800 text-xs mt-1" x-text="activeTicket?.tanggal"></span>
                    </div>
                    <div>
                        <span class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-widest">WAKTU</span>
                        <span class="block font-bold text-slate-800 text-xs mt-1" x-text="activeTicket?.waktu"></span>
                    </div>
                </div>

                <div>
                    <span class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-widest">LOKASI PENGAMBILAN</span>
                    <span class="block break-words font-bold text-slate-800 text-xs mt-0.5" x-text="activeTicket?.lokasi"></span>
                </div>

                <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl">
                    <span class="block text-[9px] font-extrabold text-emerald-800 uppercase tracking-widest">CATATAN PETUGAS</span>
                    <p class="break-words text-[10px] text-emerald-700 mt-1 leading-normal font-semibold" x-text="activeTicket?.pesan"></p>
                </div>
            </div>

            <!-- Footer Fake Barcode -->
            <div class="p-6 bg-white flex flex-col items-center justify-center border-t border-slate-100">
                <!-- Barcode Graphics -->
                <div class="w-full flex justify-center gap-0.5 mb-2 h-10 overflow-hidden opacity-85">
                    @for($i = 0; $i < 42; $i++)
                        @php $width = rand(1, 4); @endphp
                        <div class="bg-black" style="width: {{ $width }}px; height: 100%;"></div>
                    @endfor
                </div>
                <span class="font-mono text-[10px] text-slate-400" x-text="activeTicket?.kode"></span>
            </div>
        </div>
    </div>
</div>
@endsection
