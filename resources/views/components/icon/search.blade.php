{{--
|--------------------------------------------------------------------------
| Icon: Search (แว่นขยาย)
|--------------------------------------------------------------------------
|
| ตัวอย่างการใช้งาน:
| <x-icon.search class="h-5 w-5 text-gray-400" />
| <x-icon.search size="24" class="text-blue-500" />
|
--}}

@props([
    'size' => null,
    'strokeWidth' => 2,
])

<svg
    xmlns="http://www.w3.org/2000/svg"
    {{ $attributes->merge(['fill' => 'none', 'viewBox' => '0 0 24 24', 'stroke' => 'currentColor', 'aria-hidden' => 'true']) }}
    @if($size) width="{{ $size }}" height="{{ $size }}" @endif
>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
</svg>
