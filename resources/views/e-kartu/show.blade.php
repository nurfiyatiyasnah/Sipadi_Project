<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            E-Kartu Anggota
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 to-indigo-800 p-8 text-white shadow-xl">
                <div class="flex flex-col justify-between gap-8 sm:flex-row">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-200">SIPADI</p>
                        <h3 class="mt-2 text-2xl font-bold">Kartu Anggota Perpustakaan</h3>

                        <dl class="mt-8 grid gap-4 text-sm">
                            <div>
                                <dt class="text-indigo-200">Nama</dt>
                                <dd class="text-lg font-semibold">{{ $anggota->nama_lengkap }}</dd>
                            </div>
                            <div>
                                <dt class="text-indigo-200">Nomor Kartu / NIK</dt>
                                <dd class="font-mono text-lg">{{ $eKartu->no_anggota }}</dd>
                            </div>
                            <div>
                                <dt class="text-indigo-200">Berlaku Sampai</dt>
                                <dd>{{ $eKartu->masa_berlaku?->translatedFormat('d F Y') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="flex min-w-48 flex-col justify-between rounded-xl bg-white/10 p-5 text-sm">
                        <div>
                            <p class="text-indigo-200">Kalangan</p>
                            <p class="text-lg font-semibold">{{ $eKartu->kalangan }}</p>
                        </div>
                        <div class="mt-8">
                            <p class="text-indigo-200">Kode Kartu</p>
                            <p class="break-all font-mono text-xs">{{ $eKartu->barcode }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('anggota.e-kartu.download') }}" class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Download PNG
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
