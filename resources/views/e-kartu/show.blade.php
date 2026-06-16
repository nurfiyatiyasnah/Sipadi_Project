<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            E-Kartu Anggota
        </h2>
    </x-slot>

    <div class="bg-[#f6f5e9] py-12">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 rounded-3xl bg-white p-6 shadow-sm">
                <p class="text-sm font-bold uppercase tracking-widest text-[#806800]">Registrasi Berhasil</p>
                <h3 class="mt-2 font-serif text-3xl font-bold text-[#061b3a]">E-Kartu Anggota Anda Sudah Aktif</h3>
                <p class="mt-2 text-slate-600">Gunakan kartu digital ini untuk mengakses layanan SIPADI Bukittinggi.</p>
            </div>

            <div class="overflow-hidden rounded-[2rem] bg-[#061b3a] p-8 text-white shadow-2xl shadow-[#061b3a]/20">
                <div class="flex flex-col justify-between gap-8 sm:flex-row">
                    <div class="flex-1">
                        <p class="text-sm font-bold uppercase tracking-[0.24em] text-[#ffdc7c]">SIPADI Bukittinggi</p>
                        <h3 class="mt-3 text-3xl font-bold">Kartu Anggota Digital</h3>

                        <dl class="mt-8 grid gap-5 rounded-3xl bg-white/10 p-6 text-sm">
                            <div>
                                <dt class="text-slate-300">Nama Anggota</dt>
                                <dd class="mt-1 text-2xl font-bold">{{ $anggota->nama_lengkap }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-300">Nomor Kartu / NIK</dt>
                                <dd class="mt-1 font-mono text-xl font-bold">{{ $eKartu->no_anggota }}</dd>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-slate-300">Kalangan</dt>
                                    <dd class="mt-1 font-semibold">{{ $eKartu->kalangan }}</dd>
                                </div>
                                <div>
                                    <dt class="text-slate-300">Berlaku Sampai</dt>
                                    <dd class="mt-1 font-semibold">{{ $eKartu->masa_berlaku?->translatedFormat('d F Y') }}</dd>
                                </div>
                            </div>
                        </dl>
                    </div>

                    <div class="flex min-w-56 flex-col justify-between rounded-3xl bg-white p-5 text-[#061b3a]">
                        <div class="flex justify-center">
                            @if ($anggota->foto)
                                <img src="{{ asset('storage/'.$anggota->foto) }}" alt="Foto {{ $anggota->nama_lengkap }}" class="h-28 w-28 rounded-3xl object-cover ring-4 ring-[#ffdc7c]/70">
                            @else
                                <div class="flex h-28 w-28 items-center justify-center rounded-3xl bg-[#f6f5e9] text-4xl font-bold ring-4 ring-[#ffdc7c]/70">
                                    {{ mb_substr($anggota->nama_lengkap, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 rounded-2xl bg-[#f6f5e9] p-4 text-center">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Kode Kartu</p>
                            <p class="mt-2 break-all font-mono text-xs">{{ $eKartu->barcode }}</p>
                        </div>
                        <div class="mt-4 rounded-full bg-emerald-50 px-4 py-2 text-center text-sm font-bold text-emerald-700">
                            Aktif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('anggota.e-kartu.download') }}" class="rounded-2xl bg-[#061b3a] px-5 py-3 text-sm font-semibold text-white shadow hover:bg-[#0b2a59] focus:outline-none focus:ring-2 focus:ring-[#061b3a] focus:ring-offset-2">
                    Download PNG
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
