@props([
    'level' => 'h2',                 
    'size' => 'text-xl md:text-2xl',  
    'weight' => 'font-extrabold',          
    'color' => 'text-black',         
    'class' => '',                   
])

@php
    $tag = in_array($level, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']) ? $level : 'h2';
    $classes = "{$size} {$weight} {$color} {$class}";
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $tag }}>

{{-- <x-ui.heading>จัดการเกณฑ์การประเมิน</x-ui.heading> --}}

{{-- 
<x-ui.heading level="h1" size="text-3xl" weight="font-semibold" class="mb-2">
    ภาพรวมการประเมินผล
</x-ui.heading>
--}}
