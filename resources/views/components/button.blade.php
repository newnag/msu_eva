@props([
    'type' => 'primary',
    'text',
    'onclick' => null,
    'buttonType' => 'button',
    'icon' => null,
    'href' => null,
])

@php
    $class = match ($type) {
        'primary' => 'bg-blue-700 hover:bg-blue-600 text-white text-lg font-normal',
        'danger'  => 'bg-red-500 hover:bg-red-600 text-white',
        'warning' => 'bg-blue-500 hover:bg-blue-600 text-white',
        'secondary' => 'bg-white border-2 border-purple-500 hover:bg-purple-200 text-purple-500 font-semibold',

         // Outlined buttons
        'outline-primary'=> 'border border-blue-400 text-blue-600 hover:bg-blue-50',
        'outline-danger' => 'border border-red-400 text-red-600 hover:bg-red-50',
        default   => 'bg-gray-300 hover:bg-gray-400 text-gray-800',
    };
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "px-4 py-2 rounded-lg inline-flex  items-center  $class"]) }}>
        @if ($icon)
            <i class="{{ $icon }}"></i>
        @endif
        <span>{{ $text }}</span>
    </a>
@else
    <button 
        type="{{ $buttonType }}"
        @if ($onclick) onclick="{{ $onclick }}" @endif
        {{ $attributes->merge(['class' => "px-4 py-2 rounded-lg inline-flex items-center gap-3 $class"]) }}
    >
        @if ($icon)
            <i class="{{ $icon }}"></i>
        @endif
        <span>{{ $text }}</span>
    </button>
@endif
