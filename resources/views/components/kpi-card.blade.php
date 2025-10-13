@props([
  'title',
  'value' => 0,
  'unit' => null,
  'subtitle' => null,
  'decimals' => null,
  'iconContainerClass' => 'p-3 bg-blue-100 rounded-lg',
])

@php
  $displayValue = is_numeric($value)
      ? (!is_null($decimals) ? number_format($value, $decimals) : number_format($value))
      : $value;
@endphp

<div {{ $attributes->merge(['class' => 'stat-card bg-white rounded-xl p-6 drop-shadow-md']) }} role="region" aria-label="{{ $title }}">
  <div class="flex items-center justify-between">
    <div class="space-y-3">
      {{-- หัวข้อ KPI --}}
      <p class="text-xl font-medium text-gray-700">{{ $title }}</p>

      {{-- ตัวแปร & หน่วย--}}
      <p class="text-3xl font-bold text-black ml-1">
        {{ $displayValue }}
        @if($unit)
          <span class="text-xl font-medium text-gray-600">{{ $unit }}</span>
        @endif
      </p>

      {{-- คำอธิบาย/ช่วงเวลา --}}
      @if($subtitle)
        <p class="text-lg font-normal text-gray-800">{{ $subtitle }}</p>
      @endif
    </div>

    {{-- Icon --}}
    <div class="{{ $iconContainerClass }}">
      {{ $icon ?? '' }}
    </div>
  </div>
</div>
