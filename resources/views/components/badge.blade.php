@props([
    'tone' => 'neutral',
])

@php
    // Nada badge dipetakan ke kelas .tj-badge-* di app.css,
    // jadi warna status konsisten di seluruh aplikasi.
    $tones = [
        'neutral' => 'tj-badge-neutral',
        'amber'   => 'tj-badge-amber',
        'blue'    => 'tj-badge-blue',
        'indigo'  => 'tj-badge-indigo',
        'emerald' => 'tj-badge-emerald',
        'rose'    => 'tj-badge-rose',
    ];

    $toneClass = $tones[$tone] ?? $tones['neutral'];
@endphp

<span {{ $attributes->merge(['class' => "tj-badge {$toneClass}"]) }}>
    {{ $slot }}
</span>
