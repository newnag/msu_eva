@props([
  'buttonLabel' => 'ดาวน์โหลด',
  'showPng' => true,
  'showJpg' => true,
  'showPdf' => true,
  'iconPng' => null,
  'iconJpg' => null,
  'iconPdf' => null,
])

@php
$iconImage = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image">
  <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
  <circle cx="9" cy="9" r="2" />
  <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
</svg>';

$iconFile = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text">
  <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
  <path d="M14 2v4a2 2 0 0 0 2 2h4" />
  <path d="M10 9H8" />
  <path d="M16 13H8" />
  <path d="M16 17H8" />
</svg>';

$iconDownload = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
</svg>';

$pngIcon = $iconPng ?: $iconImage;
$jpgIcon = $iconJpg ?: $iconImage;
$pdfIcon = $iconPdf ?: $iconFile;

$uid = uniqid('dlm_');
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false">
  <button
    id="downloadMenuBtn-{{ $uid }}"
    type="button"
    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-gray-200 text-gray-700 hover:bg-gray-50"
    aria-haspopup="true"
    :aria-expanded="open ? 'true' : 'false'"
    aria-controls="downloadMenu-{{ $uid }}"
    @click="open = !open"
  >
    {!! $iconDownload !!}
    <span class="text-base">{{ $buttonLabel }}</span>
    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>

  <div
    id="downloadMenu-{{ $uid }}"
    class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-10 py-1 origin-top-right"
    role="menu"
    aria-labelledby="downloadMenuBtn-{{ $uid }}"
    x-cloak
    x-show="open"
    x-transition:enter="transition ease-out duration-100"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-75"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
  >
    @if ($showPng)
      <button
        type="button"
        data-dl="png"
        class="w-full text-left px-3 py-2 hover:bg-gray-50 inline-flex items-center gap-2"
        role="menuitem"
        @click="open = false"
      >
        {!! $pngIcon !!} <span>PNG</span>
      </button>
    @endif

    @if ($showJpg)
      <button
        type="button"
        data-dl="jpg"
        class="w-full text-left px-3 py-2 hover:bg-gray-50 inline-flex items-center gap-2"
        role="menuitem"
        @click="open = false"
      >
        {!! $jpgIcon !!} <span>JPEG</span>
      </button>
    @endif

    @if ($showPdf)
      <button
        type="button"
        data-dl="pdf"
        class="w-full text-left px-3 py-2 hover:bg-gray-50 inline-flex items-center gap-2"
        role="menuitem"
        @click="open = false"
      >
        {!! $pdfIcon !!} <span>PDF</span>
      </button>
    @endif
  </div>
</div>