@php
    $isEdit = isset($prestasi);
    $statusOptions = [
        \App\Models\Prestasi::STATUS_PUBLISHED => 'Terbit',
        \App\Models\Prestasi::STATUS_DRAFT => 'Draft',
        \App\Models\Prestasi::STATUS_INACTIVE => 'Nonaktif',
    ];
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

<form method="POST" action="{{ $isEdit ? route('petugas.prestasi.update', $prestasi) : route('petugas.prestasi.store') }}" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-[1fr_320px]">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="space-y-6">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center gap-2 border-b border-slate-100 pb-4">
                <i class="fa-solid fa-trophy text-[#9a7b13]"></i>
                <h3 class="font-bold text-slate-900">Informasi Prestasi</h3>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="judul_prestasi" class="mb-2 block text-sm font-bold text-slate-700">Judul Prestasi <span class="text-red-500">*</span></label>
                    <input id="judul_prestasi" name="judul_prestasi" value="{{ old('judul_prestasi', $prestasi->judul_prestasi ?? '') }}" placeholder="Contoh: Perpustakaan Digital Terbaik Tingkat Nasional" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                    @error('judul_prestasi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tingkat_prestasi" class="mb-2 block text-sm font-bold text-slate-700">Tingkat <span class="text-red-500">*</span></label>
                    <select id="tingkat_prestasi" name="tingkat_prestasi" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                        <option value="">Pilih tingkat</option>
                        <option value="lokal" @selected(old('tingkat_prestasi', $prestasi->tingkat_prestasi ?? '') === 'lokal')>Lokal</option>
                        <option value="nasional" @selected(old('tingkat_prestasi', $prestasi->tingkat_prestasi ?? '') === 'nasional')>Nasional</option>
                        <option value="internasional" @selected(old('tingkat_prestasi', $prestasi->tingkat_prestasi ?? '') === 'internasional')>Internasional</option>
                    </select>
                    @error('tingkat_prestasi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_prestasi" class="mb-2 block text-sm font-bold text-slate-700">Tanggal Prestasi</label>
                    <input id="tanggal_prestasi" type="date" name="tanggal_prestasi" value="{{ old('tanggal_prestasi', optional($prestasi->tanggal_prestasi ?? null)->format('Y-m-d')) }}" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                    @error('tanggal_prestasi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="penyelenggara" class="mb-2 block text-sm font-bold text-slate-700">Penyelenggara</label>
                    <input id="penyelenggara" name="penyelenggara" value="{{ old('penyelenggara', $prestasi->penyelenggara ?? '') }}" placeholder="Nama instansi penyelenggara" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                    @error('penyelenggara')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nomor_sertifikat" class="mb-2 block text-sm font-bold text-slate-700">Nomor Sertifikat/SK</label>
                    <input id="nomor_sertifikat" name="nomor_sertifikat" value="{{ old('nomor_sertifikat', $prestasi->nomor_sertifikat ?? '') }}" placeholder="Contoh: SK-PRESTASI/2026/001" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                    @error('nomor_sertifikat')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="deskripsi" class="mb-2 block text-sm font-bold text-slate-700">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="7" placeholder="Jelaskan ringkasan prestasi, pemberi penghargaan, dan dampaknya..." class="w-full resize-none rounded-xl border-slate-200 bg-slate-50 text-sm leading-6 focus:border-[#ffd15c] focus:ring-[#ffd15c]">{{ old('deskripsi', $prestasi->deskripsi ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>
    </div>

    <aside class="space-y-5">
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <label for="status_prestasi" class="mb-2 block text-sm font-bold text-slate-700">Status Publikasi</label>
            <select id="status_prestasi" name="status_prestasi" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-[#ffd15c] focus:ring-[#ffd15c]">
                @foreach ($statusOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('status_prestasi', $prestasi->status_prestasi ?? \App\Models\Prestasi::STATUS_DRAFT) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status_prestasi')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </section>

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            @if ($isEdit && $prestasi->gambar)
                <img src="{{ Storage::url($prestasi->gambar) }}" alt="{{ $prestasi->judul_prestasi }}" class="aspect-video w-full object-cover">
            @else
                <div class="flex aspect-video items-center justify-center bg-gradient-to-br from-[#0e1f30] via-[#344c5f] to-[#c6a33a] text-4xl text-white/80">
                    <i class="fa-solid fa-award"></i>
                </div>
            @endif
            <div class="space-y-3 p-5">
                <label for="gambar" class="block text-sm font-bold text-slate-900">Foto Dokumentasi</label>
                <p class="text-xs text-slate-500">Format JPG, PNG, atau WebP. Maksimal 4MB.</p>
                <input id="gambar" name="gambar" type="file" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-xs file:font-bold file:text-slate-700">
                @error('gambar')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <label for="file_lampiran" class="block text-sm font-bold text-slate-900">Lampiran</label>
            <p class="mt-1 text-xs text-slate-500">PDF atau gambar sertifikat. Maksimal 5MB.</p>
            @if ($isEdit && $prestasi->file_lampiran)
                <a href="{{ Storage::url($prestasi->file_lampiran) }}" target="_blank" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-[#7c6312] hover:text-[#0e1f30]">
                    <i class="fa-regular fa-file-lines"></i>
                    Lihat lampiran saat ini
                </a>
            @endif
            <input id="file_lampiran" name="file_lampiran" type="file" accept=".pdf,image/*" class="mt-4 block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-xs file:font-bold file:text-slate-700">
            @error('file_lampiran')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </section>

        <div class="grid gap-3">
            <button type="submit" class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-[#0e1f30] text-sm font-bold text-white shadow-sm transition hover:bg-[#1a2f44]">
                <i class="fa-solid fa-floppy-disk"></i>
                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Prestasi' }}
            </button>
            <a href="{{ route('petugas.prestasi.index') }}" class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white text-sm font-bold text-slate-700 transition hover:bg-slate-50">Batal</a>
        </div>
    </aside>
</form>
