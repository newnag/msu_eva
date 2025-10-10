{{--
|--------------------------------------------------------------------------
| Icon Component: Plus ( + )
|--------------------------------------------------------------------------
| Usage Examples:
|
| 1. ใช้ร่วมกับ Tailwind กำหนดขนาด:
|    <x-icon.plus class="h-5 w-5 mr-2" />

| 2. ระบุขนาดแบบกำหนดเอง (หน่วยเป็น px หรือ em):
|    <x-icon.plus size="24" class="mr-2" />
|
| Props:
|  - size         : (optional) กำหนดขนาดของไอคอน (width/height)
|  - strokeWidth  : (default: 2) ความหนาเส้นของเส้นไอคอน
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
          d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
</svg>


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
          d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
</svg>

