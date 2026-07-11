@props([
    'book' => null,
    'src' => null,
    'alt' => null,
    'iconClass' => 'text-3xl',
    'imageClass' => 'object-cover',
])

@php
    $coverUrl = $src;

    if (! $coverUrl && is_object($book) && method_exists($book, 'coverUrl')) {
        $coverUrl = $book->coverUrl();
    }

    $bookTitle = is_object($book) && isset($book->judul) ? $book->judul : null;
    $altText = $alt ?? ($bookTitle ? 'Cover '.$bookTitle : 'Cover buku');
@endphp

<div {{ $attributes->class('relative flex-shrink-0 overflow-hidden border border-slate-200 bg-slate-50 text-slate-400') }}>
    @if ($coverUrl)
        <img
            src="{{ $coverUrl }}"
            alt="{{ $altText }}"
            class="h-full w-full {{ $imageClass }}"
            onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');"
        >
    @endif

    <div class="{{ $coverUrl ? 'hidden' : 'flex' }} absolute inset-0 h-full w-full items-center justify-center bg-slate-50 text-slate-400">
        <i class="fa-solid fa-book {{ $iconClass }}" aria-hidden="true"></i>
    </div>
</div>
