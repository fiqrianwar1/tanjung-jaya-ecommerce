@props([
    'label',
    'value',
    'icon' => null,
    'tone' => 'emerald',
    'hint' => null,
])

@php
    // Warna ikon per nada. Dikumpulkan di sini supaya pemanggil cukup
    // menulis tone="blue" tanpa menghafal kelas Tailwind.
    $tones = [
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'blue'    => 'bg-blue-50 text-blue-600',
        'amber'   => 'bg-amber-50 text-amber-600',
        'rose'    => 'bg-rose-50 text-rose-600',
        'indigo'  => 'bg-indigo-50 text-indigo-600',
        'slate'   => 'bg-slate-100 text-slate-600',
    ];

    $toneClass = $tones[$tone] ?? $tones['emerald'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col group hover:shadow-xl hover:border-emerald-100 transition-all duration-300 hover:-translate-y-1 relative overflow-hidden']) }}>
    @if($icon)
    <div class="flex items-center justify-between mb-4 relative z-10">
        <div class="w-12 h-12 rounded-2xl {{ $toneClass }} group-hover:scale-110 flex items-center justify-center transition-transform duration-300 shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
            </svg>
        </div>
    </div>
    @endif

    <div class="relative z-10">
        <h4 class="text-slate-500 font-semibold text-xs mb-1 uppercase tracking-wider">{{ $label }}</h4>
        <div class="text-2xl font-black text-slate-800">{{ $value }}</div>

        @if($hint)
            <p class="text-xs text-slate-400 mt-1">{{ $hint }}</p>
        @endif
    </div>

    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-gradient-to-br from-slate-50 to-transparent rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
</div>
