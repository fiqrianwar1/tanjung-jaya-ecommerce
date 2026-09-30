@props([
    'title',
    'subtitle' => null,
    'icon' => null,
    'tone' => 'emerald',
    'href' => null,
    'linkLabel' => 'Lihat Semua',
])

@php
    $tones = [
        'emerald' => 'bg-emerald-100 text-emerald-600',
        'blue'    => 'bg-blue-100 text-blue-600',
        'amber'   => 'bg-amber-100 text-amber-600',
        'rose'    => 'bg-rose-100 text-rose-600',
        'indigo'  => 'bg-indigo-100 text-indigo-600',
        'slate'   => 'bg-slate-100 text-slate-600',
    ];

    $toneClass = $tones[$tone] ?? $tones['emerald'];
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col sm:flex-row sm:items-center gap-3 mb-6']) }}>
    @if($icon)
    <div class="{{ $toneClass }} p-2 rounded-xl shrink-0">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path d="{{ $icon }}"></path>
        </svg>
    </div>
    @endif

    <div class="min-w-0">
        <h2 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">{{ $title }}</h2>

        @if($subtitle)
            <p class="tj-muted mt-0.5">{{ $subtitle }}</p>
        @endif
    </div>

    @if($href)
        <a href="{{ $href }}" class="tj-btn tj-btn-sm tj-btn-soft sm:ml-auto shrink-0">
            {{ $linkLabel }}
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    @endif

    {{ $slot ?? '' }}
</div>
