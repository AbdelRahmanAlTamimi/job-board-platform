@props([
    'type' => 'success',
    'message',
])

@php
    $styles = [
        'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
        'error' => 'bg-rose-50 border-rose-200 text-rose-800',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
        'info' => 'bg-sky-50 border-sky-200 text-sky-800',
    ];

    $selected = $styles[$type] ?? $styles['info'];
@endphp

<div {{ $attributes->merge(['class' => "px-4 py-3 mb-4 rounded-md border {$selected} flex items-start space-x-2"]) }} role="alert">
    <span class="text-lg leading-none">
        @switch($type)
            @case('success')
                ✓
                @break
            @case('error')
                !
                @break
            @case('warning')
                !
                @break
            @default
                ℹ
        @endswitch
    </span>
    <div class="text-sm">{{ $message }}</div>
</div>
