<div wire:poll.5s class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
    @foreach ([
        ['total', 'Total Layanan', $stats['total'], 'fa-solid fa-grip', 'bg-slate-100 text-slate-700'],
        ['aktif', 'Aktif', $stats['aktif'], 'fa-solid fa-circle-check', 'bg-emerald-100 text-emerald-700'],
        ['review', 'Perlu Review', $stats['review'], 'fa-solid fa-clock-rotate-left', 'bg-orange-100 text-orange-600'],
        ['akses', 'Total Akses', $stats['akses'], 'fa-solid fa-eye', 'bg-blue-100 text-blue-700'],
    ] as [$key, $label, $value, $icon, $iconClass])
        <div wire:key="layanan-stat-{{ $key }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ $iconClass }}">
                    <i class="{{ $icon }}"></i>
                </span>
                <div>
                    <p class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">{{ $label }}</p>
                    <p class="mt-1 text-2xl font-black text-slate-900">{{ number_format($value) }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>
