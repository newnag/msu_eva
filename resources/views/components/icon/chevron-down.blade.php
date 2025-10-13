{{--
|--------------------------------------------------------------------------
| Icon: Chevron Down
|--------------------------------------------------------------------------
|
| ใช้สำหรับ dropdown เช่น select หรือ accordion
| Usage:
|   <x-icon.chevron-down class="h-4 w-4 text-gray-500" />
|
--}}

@props([
    'size' => 16,        
    'strokeWidth' => 2,   
    'class' => '', 
])

<svg xmlns="http://www.w3.org/2000/svg"
     fill="none"
     viewBox="0 0 24 24"
     stroke="currentColor"
     class="{{ $class }}"
     width="{{ $size }}"
     height="{{ $size }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
          d="M19 9l-7 7-7-7" />
</svg>
