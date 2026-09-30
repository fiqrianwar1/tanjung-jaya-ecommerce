@props([
    'title' => 'Belum ada data',
    'description' => null,
    'icon' => null,
    'actionHref' => null,
    'actionLabel' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-3xl border border-slate-100 p-12 sm:p-16 text-center shadow-sm']) }}>
    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-slate-100">
        @if($icon)
            <svg class="w-11 h-11 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"></path>
            </svg>
        @else
            <svg class="w-11 h-11 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
        @endif
    </div>

    <h3 class="text-lg font-bold text-slate-800 mb-1">{{ $title }}</h3>

    @if($description)
        <p class="text-slate-500">{{ $description }}</p>
    @endif

    @if($actionHref && $actionLabel)
        <a href="{{ $actionHref }}" class="tj-btn tj-btn-md tj-btn-primary mt-6">
            {{ $actionLabel }}
        </a>
    @endif

    {{ $slot ?? '' }}
</div>
