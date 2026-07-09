@extends('layouts.petugas')
@section('title', 'Update Data Anggota')

@section('content')
<div class="mx-auto max-w-[1280px]" x-data="{ administrativeModal: @js(old('administrative_action')) }" @keydown.escape.window="administrativeModal = null">
    <!-- Breadcrumbs -->
    <div class="mb-4 text-xs font-semibold text-slate-500 flex items-center gap-2">
        <span class="hover:text-slate-700">Dashboard</span>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <a href="{{ route('petugas.anggota.index') }}" class="hover:text-slate-700">Anggota</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="text-slate-800">Update Data Anggota</span>
    </div>

    <!-- Heading -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-slate-850">Update Data Anggota</h2>
        <p class="text-sm text-slate-500 mt-1">Perbarui informasi profil dan status akun anggota.</p>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
        <div class="mb-6 flex items-center justify-between p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold transition" id="success-alert">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="document.getElementById('success-alert').remove()" class="text-emerald-500 hover:text-emerald-800 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-sm">
            <div class="flex items-center gap-2 mb-2 font-bold">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Terdapat kesalahan pengisian data:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Container -->
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100">
        <form method="POST" action="{{ route('petugas.anggota.update', $anggota->id_anggota) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Upper Row: Photo & Read-only NIK / ID Anggota -->
            <div class="flex flex-col md:flex-row gap-8 items-start pb-8 border-b border-slate-100 mb-8">
                <!-- Profile Photo Upload -->
                <div class="flex flex-col items-center text-center w-full md:w-auto md:shrink-0" x-data="{ photoPreview: null }">
                    <div class="relative mb-3">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" alt="Preview Foto" class="h-32 w-32 rounded-full object-cover border border-slate-200 shadow-md">
                        </template>
                        <template x-if="!photoPreview">
                            @if ($anggota->foto)
                                <img src="{{ asset('storage/' . $anggota->foto) }}" alt="{{ $anggota->nama_lengkap }}" class="h-32 w-32 rounded-full object-cover border border-slate-200 shadow-md">
                            @else
                                <div class="h-32 w-32 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-400 text-2xl border border-slate-200 shadow-md">
                                    {{ strtoupper(substr($anggota->nama_lengkap, 0, 2)) }}
                                </div>
                            @endif
                        </template>
                    </div>

                    <!-- Hidden input file -->
                    <input type="file" name="foto" id="foto-input" class="hidden" accept="image/*" 
                        onchange="
                            const file = this.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const previewImg = document.createElement('img');
                                    // Set data context or simple JS element modification
                                    const img = document.querySelector('[alt=\'Preview Foto\']') || document.querySelector('[alt=\'{{ $anggota->nama_lengkap }}\']') || document.querySelector('.rounded-full');
                                    if(img) img.src = e.target.result;
                                }
                                reader.readAsDataURL(file);
                            }
                        ">
                    
                    <p class="text-[10px] text-slate-400 leading-normal max-w-[180px] mb-3">Allowed format: JPG, PNG. Max size: 2MB</p>
                    <button type="button" onclick="document.getElementById('foto-input').click()" class="text-xs font-bold text-[#7c6312] hover:text-[#634e0e] hover:underline transition">
                        Upload Foto Baru
                    </button>
                </div>

                <!-- Read-only inputs -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 flex-1 w-full">
                    <!-- NIK (Read-only) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" value="{{ $anggota->nik }}" disabled class="w-full rounded-xl border border-slate-200 bg-slate-100 py-3 px-4 text-sm text-slate-500 cursor-not-allowed outline-none">
                        <span class="block text-[10px] text-slate-400 mt-1.5">NIK tidak dapat diubah setelah terdaftar.</span>
                    </div>

                    <!-- ID Anggota (Read-only) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">ID Anggota / No. Anggota</label>
                        <input type="text" value="{{ $anggota->no_anggota }}" disabled class="w-full rounded-xl border border-slate-200 bg-slate-100 py-3 px-4 text-sm text-slate-500 cursor-not-allowed outline-none">
                    </div>
                </div>
            </div>

            <!-- Lower Row: Form Inputs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Input Fields -->
                <div class="space-y-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama_lengkap" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 px-4 text-sm text-slate-800 outline-none transition focus:border-slate-300 focus:bg-white">
                    </div>

                    <!-- Email Aktif -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Email Aktif <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $anggota->user?->email) }}" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 px-4 text-sm text-slate-800 outline-none transition focus:border-slate-300 focus:bg-white">
                    </div>

                    <!-- No Telepon / WhatsApp -->
                    <div>
                        <label for="no_telepon" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">No. Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                        <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', $anggota->no_telepon) }}" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 px-4 text-sm text-slate-800 outline-none transition focus:border-slate-300 focus:bg-white">
                    </div>
                </div>

                <!-- Right Input Fields -->
                <div class="space-y-6">
                    <!-- Alamat Lengkap -->
                    <div>
                        <label for="alamat" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                        <textarea name="alamat" id="alamat" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 px-4 text-sm text-slate-800 outline-none transition focus:border-slate-300 focus:bg-white leading-relaxed">{{ old('alamat', $anggota->alamat) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Status Anggota</span>
                            <div class="min-h-[52px] rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $statusAnggota['class'] }}">
                                    {{ $statusAnggota['label'] }}
                                </span>
                                <p class="mt-2 text-[10px] leading-normal text-slate-400">{{ $statusAnggota['description'] }}</p>
                            </div>
                        </div>

                        <div>
                            <span class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Status Sanksi</span>
                            <div class="min-h-[52px] rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $statusSanksi['class'] }}">
                                    {{ $statusSanksi['label'] }}
                                </span>
                                <p class="mt-2 text-[10px] leading-normal text-slate-400">{{ $statusSanksi['description'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Buttons -->
            <div class="mt-10 flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                <a href="{{ route('petugas.anggota.show', $anggota->id_anggota) }}" class="flex items-center justify-center px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition shadow-sm">
                    Batal
                </a>
                <button type="submit" class="flex items-center justify-center px-6 py-3 rounded-xl bg-[#7c6312] text-white font-bold text-sm hover:bg-[#634e0e] transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Administrative Actions -->
    <div class="mt-8 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
            <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-600">
                <i class="fa-solid fa-shield-halved text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-850">Aksi Administratif</h3>
                <p class="text-xs text-slate-400">Tindakan khusus untuk membatasi akun atau hak peminjaman anggota.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="rounded-xl border {{ $isAnggotaAktif ? 'border-rose-100 bg-rose-50/20' : 'border-emerald-100 bg-emerald-50/20' }} p-5">
                <div class="flex items-start gap-4">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center border shrink-0 {{ $isAnggotaAktif ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }}">
                        <i class="fa-solid {{ $isAnggotaAktif ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="text-sm font-extrabold text-slate-800">{{ $isAnggotaAktif ? 'Nonaktifkan Anggota' : 'Aktifkan Anggota' }}</h4>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500">
                            {{ $isAnggotaAktif ? 'Menonaktifkan akun anggota dan akses layanan anggota.' : 'Mengaktifkan kembali akun anggota dan akses layanan anggota.' }}
                        </p>
                        <p class="mt-4 text-xs font-semibold text-slate-500">Status saat ini: <span class="font-bold text-slate-800">{{ $statusAnggota['label'] }}</span></p>
                    </div>
                </div>
                <div class="mt-5 flex justify-end">
                    @if ($isAnggotaAktif)
                        <button type="button" @click="administrativeModal = 'deactivate'" class="inline-flex items-center justify-center gap-2 rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-xs font-bold text-rose-700 shadow-sm transition hover:bg-rose-50">
                            <i class="fa-solid fa-user-slash"></i>
                            Nonaktifkan Anggota
                        </button>
                    @else
                        <button type="button" @click="administrativeModal = 'activate'" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-emerald-700">
                            <i class="fa-solid fa-user-check"></i>
                            Aktifkan Anggota
                        </button>
                    @endif
                </div>
            </div>

            <div class="rounded-xl border {{ $isBorrowingBlocked ? 'border-emerald-100 bg-emerald-50/20' : 'border-amber-100 bg-amber-50/20' }} p-5">
                <div class="flex items-start gap-4">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center border shrink-0 {{ $isBorrowingBlocked ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                        <i class="fa-solid {{ $isBorrowingBlocked ? 'fa-lock-open' : 'fa-ban' }}"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="text-sm font-extrabold text-slate-800">{{ $isBorrowingBlocked ? 'Buka Blokir Peminjaman' : 'Blokir Peminjaman' }}</h4>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500">
                            {{ $isBorrowingBlocked ? 'Menyelesaikan blokir peminjaman aktif untuk anggota.' : 'Membatasi anggota agar tidak dapat melakukan peminjaman.' }}
                        </p>
                        <p class="mt-4 text-xs font-semibold text-slate-500">Status saat ini: <span class="font-bold text-slate-800">{{ $statusSanksi['label'] }}</span></p>
                    </div>
                </div>
                <div class="mt-5 flex justify-end">
                    @if (! $isBorrowingBlocked)
                        <button type="button" @click="administrativeModal = 'blockBorrowing'" class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-amber-600">
                            <i class="fa-solid fa-ban"></i>
                            Blokir Peminjaman
                        </button>
                    @else
                        <button type="button" @click="administrativeModal = 'unblockBorrowing'" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-emerald-700">
                            <i class="fa-solid fa-lock-open"></i>
                            Buka Blokir Peminjaman
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Deactivate Member Modal -->
    <div x-show="administrativeModal === 'deactivate'" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/45 p-4">
        <div class="absolute inset-0" @click="administrativeModal = null"></div>
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-100" @click.stop>
            <form method="POST" action="{{ route('petugas.anggota.nonaktifkan', $anggota->id_anggota) }}">
                @csrf
                <input type="hidden" name="administrative_action" value="deactivate">

                <div class="flex items-start gap-4">
                    <div class="h-12 w-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100 shrink-0">
                        <i class="fa-solid fa-user-slash text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-lg font-extrabold text-slate-850">Nonaktifkan Anggota</h3>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500">Anggota ini akan dinonaktifkan dan aksesnya ke layanan anggota akan dibatasi.</p>
                    </div>
                </div>

                <div class="mt-5 rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3">
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Nama Anggota</span>
                    <span class="mt-1 block text-sm font-bold text-slate-800">{{ $anggota->nama_lengkap }}</span>
                </div>

                <div class="mt-5">
                    <label for="alasan_nonaktif" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Alasan Nonaktif <span class="text-rose-500">*</span></label>
                    <textarea name="alasan_nonaktif" id="alasan_nonaktif" rows="4" required placeholder="Tuliskan alasan nonaktif..." class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 px-4 text-sm text-slate-800 outline-none transition focus:border-rose-200 focus:bg-white">{{ old('alasan_nonaktif') }}</textarea>
                    <p class="mt-1.5 text-[10px] leading-normal text-slate-400">Alasan diperlukan sebagai catatan administratif.</p>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" @click="administrativeModal = null" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-rose-700">
                        Nonaktifkan Anggota
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Activate Member Modal -->
    <div x-show="administrativeModal === 'activate'" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/45 p-4">
        <div class="absolute inset-0" @click="administrativeModal = null"></div>
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-100" @click.stop>
            <form method="POST" action="{{ route('petugas.anggota.aktifkan', $anggota->id_anggota) }}">
                @csrf
                <input type="hidden" name="administrative_action" value="activate">

                <div class="flex items-start gap-4">
                    <div class="h-12 w-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100 shrink-0">
                        <i class="fa-solid fa-user-check text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-lg font-extrabold text-slate-850">Aktifkan Anggota</h3>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500">Akun anggota ini akan diaktifkan kembali dan dapat mengakses layanan anggota.</p>
                    </div>
                </div>

                <div class="mt-5 rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3">
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Nama Anggota</span>
                    <span class="mt-1 block text-sm font-bold text-slate-800">{{ $anggota->nama_lengkap }}</span>
                </div>

                <div class="mt-5 rounded-xl border border-emerald-100 bg-emerald-50/40 px-4 py-3">
                    <p class="text-xs leading-relaxed text-emerald-700">Status anggota dan akun login akan dikembalikan menjadi aktif. Alasan nonaktif sebelumnya akan dikosongkan.</p>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" @click="administrativeModal = null" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">
                        Aktifkan Anggota
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Block Borrowing Modal -->
    <div x-show="administrativeModal === 'blockBorrowing'" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/45 p-4">
        <div class="absolute inset-0" @click="administrativeModal = null"></div>
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-100" @click.stop>
            <form method="POST" action="{{ route('petugas.anggota.blokir-peminjaman', $anggota->id_anggota) }}">
                @csrf
                <input type="hidden" name="administrative_action" value="blockBorrowing">

                <div class="flex items-start gap-4">
                    <div class="h-12 w-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100 shrink-0">
                        <i class="fa-solid fa-ban text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-lg font-extrabold text-slate-850">Blokir Peminjaman</h3>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500">Anggota tetap aktif, tetapi tidak dapat melakukan peminjaman selama blokir aktif.</p>
                    </div>
                </div>

                <div class="mt-5 rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3">
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Nama Anggota</span>
                    <span class="mt-1 block text-sm font-bold text-slate-800">{{ $anggota->nama_lengkap }}</span>
                </div>

                <div class="mt-5">
                    <label for="alasan_blokir" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Alasan Blokir <span class="text-rose-500">*</span></label>
                    <textarea name="alasan_blokir" id="alasan_blokir" rows="4" required placeholder="Tuliskan alasan blokir peminjaman..." class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 px-4 text-sm text-slate-800 outline-none transition focus:border-amber-200 focus:bg-white">{{ old('alasan_blokir') }}</textarea>
                </div>

                <div class="mt-5">
                    <label for="tanggal_selesai" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Berlaku Sampai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 px-4 text-sm text-slate-800 outline-none transition focus:border-amber-200 focus:bg-white">
                    <p class="mt-1.5 text-[10px] leading-normal text-slate-400">Kosongkan tanggal jika blokir berlaku tanpa batas.</p>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" @click="administrativeModal = null" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-amber-600">
                        Blokir Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Unblock Borrowing Modal -->
    <div x-show="administrativeModal === 'unblockBorrowing'" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/45 p-4">
        <div class="absolute inset-0" @click="administrativeModal = null"></div>
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-100" @click.stop>
            <form method="POST" action="{{ route('petugas.anggota.buka-blokir-peminjaman', $anggota->id_anggota) }}">
                @csrf
                <input type="hidden" name="administrative_action" value="unblockBorrowing">

                <div class="flex items-start gap-4">
                    <div class="h-12 w-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100 shrink-0">
                        <i class="fa-solid fa-lock-open text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-lg font-extrabold text-slate-850">Buka Blokir Peminjaman</h3>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500">Blokir peminjaman aktif akan diselesaikan. Anggota dapat mengajukan peminjaman kembali jika tidak ada sanksi aktif lain.</p>
                    </div>
                </div>

                <div class="mt-5 rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3">
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Nama Anggota</span>
                    <span class="mt-1 block text-sm font-bold text-slate-800">{{ $anggota->nama_lengkap }}</span>
                </div>

                <div class="mt-5">
                    <label for="catatan_buka_blokir" class="block text-xs font-bold text-slate-450 uppercase tracking-wider mb-2">Catatan Buka Blokir</label>
                    <textarea name="catatan_buka_blokir" id="catatan_buka_blokir" rows="3" placeholder="Tuliskan catatan buka blokir jika diperlukan..." class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 px-4 text-sm text-slate-800 outline-none transition focus:border-emerald-200 focus:bg-white">{{ old('catatan_buka_blokir') }}</textarea>
                    <p class="mt-1.5 text-[10px] leading-normal text-slate-400">Catatan bersifat opsional dan tidak mengubah sanksi keterlambatan lain.</p>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" @click="administrativeModal = null" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">
                        Buka Blokir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
