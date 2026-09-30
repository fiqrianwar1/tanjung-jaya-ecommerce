@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
])

@php
    $variants = [
        'primary'   => 'tj-btn-primary',
        'secondary' => 'tj-btn-secondary',
        'outline'   => 'tj-btn-outline',
        'soft'      => 'tj-btn-soft',
        'danger'    => 'tj-btn-danger',
    ];

    $sizes = [
        'sm' => 'tj-btn-sm',
        'md' => 'tj-btn-md',
        'lg' => 'tj-btn-lg',
    ];

    $class = 'tj-btn '.($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $class, 'type' => $attributes->get('type', 'submit')]) }}>
        {{ $slot }}
    </button>
@endif
