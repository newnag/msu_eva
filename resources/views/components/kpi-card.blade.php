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

<div {{ $attributes->merge(['class' => 'stat-card bg-white rounded-xl p-6 shadow-lg']) }} role="region" aria-label="{{ $title }}">
  <div class="flex items-center justify-between">
    <div class="space-y-2">
      {{-- หัวข้อ KPI --}}
      <p class="text-base font-medium text-gray-600">{{ $title }}</p>

      {{-- ตัวแปร & หน่วย--}}
      <p class="text-4xl font-bold text-gray-900">
        {{ $displayValue }}
        @if($unit)
          <span class="text-xl font-normal text-gray-500">{{ $unit }}</span>
        @endif
      </p>

      {{-- คำอธิบาย/ช่วงเวลา --}}
      @if($subtitle)
        <p class="text-base font-normal text-gray-600">{{ $subtitle }}</p>
      @endif
    </div>

    {{-- Icon --}}
    <div class="{{ $iconContainerClass }}">
      {{ $icon ?? '' }}
    </div>
  </div>
</div>
