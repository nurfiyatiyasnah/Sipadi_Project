@php
    $isEdit = isset($layanan);
    $requirementLines = old('persyaratan', $isEdit ? preg_split('/\r\n|\r|\n/', (string) $layanan->persyaratan) : ['Menunjukkan Kartu Tanda Anggota aktif', 'Tidak memiliki tunggakan denda keterlambatan']);
    $procedureLines = old('prosedur', $isEdit ? preg_split('/\r\n|\r|\n/', (string) $layanan->prosedur) : ['Pengunjung mengajukan permintaan layanan', 'Petugas melakukan verifikasi data', 'Layanan diproses sesuai jadwal operasional']);
@endphp

@if ($errors->any())
    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="font-bold">Terdapat kesalahan pada form:</p>
        <ul class="mt-2 list-inside list-disc space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ $isEdit ? route('petugas.layanan.update', $layanan) : route('petugas.layanan.store') }}" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-[1fr_300px]">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="space-y-6">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center gap-2 border-b border-slate-100 pb-4">
                <i class="fa-solid fa-file-lines text-[#9a7b13]"></i>
                <h3 class="font-bold text-slate-900">Informasi Layanan</h3>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="nama_layanan" class="mb-2 block text-sm font-bold text-slate-700">Nama Layanan</label>
                    <input id="nama_layanan" name="nama_layanan" value="{{ old('nama_layanan', $layanan->nama_layanan ?? '') }}" placeholder="Contoh: Peminjaman Koleksi Digital" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                </div>
                <div>
                    <label for="kategori_layanan" class="mb-2 block text-sm font-bold text-slate-700">Kategori Layanan</label>
                    <select id="kategori_layanan" name="kategori_layanan" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                        <option>Publik</option>
                        <option>Internal</option>
                        <option>Fasilitas</option>
                    </select>
                </div>
                <div>
                    <label for="status_layanan" class="mb-2 block text-sm font-bold text-slate-700">Status Layanan</label>
                    <select id="status_layanan" name="status_layanan" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                        @foreach (['aktif' => 'Aktif', 'review' => 'Perlu Review', 'maintenance' => 'Maintenance', 'nonaktif' => 'Non-Aktif'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status_layanan', $layanan->status_layanan ?? 'aktif') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="jam_layanan" class="mb-2 block text-sm font-bold text-slate-700">Jam Operasional</label>
                    <input id="jam_layanan" name="jam_layanan" value="{{ old('jam_layanan', $layanan->jam_layanan ?? '08:00 - 16:00 WIB') }}" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                </div>
                <div>
                    <label for="biaya" class="mb-2 block text-sm font-bold text-slate-700">Biaya Layanan</label>
                    <input id="biaya" name="biaya" value="{{ old('biaya', $layanan->biaya ?? 'Gratis') }}" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                </div>
                <div class="md:col-span-2">
                    <label for="deskripsi" class="mb-2 block text-sm font-bold text-slate-700">Deskripsi Singkat <span class="text-red-500">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" required placeholder="Jelaskan secara ringkas mengenai cakupan layanan ini..." class="w-full resize-none rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">{{ old('deskripsi', $layanan->deskripsi ?? '') }}</textarea>
                </div>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center gap-2 border-b border-slate-100 pb-4">
                <i class="fa-solid fa-list-check text-[#9a7b13]"></i>
                <h3 class="font-bold text-slate-900">Persyaratan & Prosedur</h3>
            </div>

            <div class="space-y-3">
                @foreach ($requirementLines as $index => $line)
                    <input name="persyaratan[]" value="{{ $line }}" placeholder="Persyaratan layanan" class="h-11 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                @endforeach
                <input name="persyaratan[]" placeholder="+ Tambah persyaratan" class="h-11 w-full rounded-xl border-dashed border-slate-300 bg-white text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
            </div>

            <div class="mt-6 space-y-3">
                @foreach ($procedureLines as $index => $line)
                    <input name="prosedur[]" value="{{ $line }}" placeholder="Prosedur layanan" class="h-11 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                @endforeach
                <input name="prosedur[]" placeholder="+ Tambah prosedur" class="h-11 w-full rounded-xl border-dashed border-slate-300 bg-white text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
            </div>
        </section>
    </div>

    <aside class="space-y-5">
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm" x-data="{ 
            selectedIcon: '{{ old('ikon', $layanan->ikon ?? 'fa-handshake-angle') }}', 
            showMore: false,
            icons: ['fa-handshake-angle', 'fa-book-open', 'fa-print', 'fa-laptop', 'fa-wifi', 'fa-envelope-open-text', 'fa-building'],
            moreIcons: ['fa-users', 'fa-chalkboard-user', 'fa-graduation-cap', 'fa-book-quran', 'fa-computer', 'fa-mouse', 'fa-keyboard', 'fa-headphones', 'fa-wheelchair', 'fa-magnifying-glass', 'fa-folder-open', 'fa-id-card']
        }">
            <h3 class="text-sm font-bold text-slate-900">Ikon Layanan</h3>
            <input type="hidden" name="ikon" x-model="selectedIcon">
            
            <div class="mt-4 grid grid-cols-4 gap-3">
                <template x-for="icon in icons" :key="icon">
                    <button type="button" 
                            @click="selectedIcon = icon"
                            :class="selectedIcon === icon ? 'border-[#d7ad37] bg-[#fff8df] text-[#d7ad37]' : 'border-slate-200 text-slate-600 hover:border-[#d7ad37] hover:bg-[#fff8df]'"
                            class="flex h-11 items-center justify-center rounded-xl border transition">
                        <i class="fa-solid" :class="icon"></i>
                    </button>
                </template>
                
                <button type="button" 
                        @click="showMore = !showMore"
                        :class="showMore ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:border-[#d7ad37] hover:bg-[#fff8df]'"
                        class="flex h-11 items-center justify-center rounded-xl border border-slate-200 transition"
                        title="Lihat ikon lainnya">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
            </div>

            <!-- More Icons -->
            <div x-show="showMore" x-transition class="mt-3 border-t border-slate-100 pt-3">
                <div class="grid grid-cols-4 gap-3">
                    <template x-for="icon in moreIcons" :key="icon">
                        <button type="button" 
                                @click="selectedIcon = icon"
                                :class="selectedIcon === icon ? 'border-[#d7ad37] bg-[#fff8df] text-[#d7ad37]' : 'border-slate-200 text-slate-600 hover:border-[#d7ad37] hover:bg-[#fff8df]'"
                                class="flex h-11 items-center justify-center rounded-xl border transition">
                            <i class="fa-solid" :class="icon"></i>
                        </button>
                    </template>
                </div>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900">Pengaturan Visibilitas</h3>
            <div class="mt-4 space-y-4">
                <label class="flex items-center justify-between gap-4">
                    <span class="text-sm font-semibold text-slate-700">Tampilkan di Publik</span>
                    <input type="checkbox" checked class="rounded border-slate-300 text-[#9a7b13] focus:ring-[#ffd15c]">
                </label>
                <label class="flex items-center justify-between gap-4">
                    <span class="text-sm font-semibold text-slate-700">Pendaftaran Online</span>
                    <input type="checkbox" class="rounded border-slate-300 text-[#9a7b13] focus:ring-[#ffd15c]">
                </label>
            </div>
        </section>

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex aspect-video items-center justify-center bg-gradient-to-br from-[#0e1f30] via-[#344c5f] to-[#c6a33a] text-4xl text-white/80">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="space-y-3 p-5">
                <label for="gambar" class="block text-sm font-bold text-slate-900">Banner Layanan</label>
                <p class="text-xs text-slate-500">Rasio 16:9, format JPG/PNG/WebP.</p>
                <input id="gambar" name="gambar" type="file" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-xs file:font-bold file:text-slate-700">
            </div>
        </section>

        <div class="grid gap-3">
            <button type="submit" class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-[#0e1f30] text-sm font-bold text-white shadow-sm transition hover:bg-[#1a2f44]">
                <i class="fa-solid fa-floppy-disk"></i>
                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Layanan' }}
            </button>
            <a href="{{ route('petugas.layanan.index') }}" class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white text-sm font-bold text-slate-700 transition hover:bg-slate-50">Batal</a>
        </div>
    </aside>
</form>
