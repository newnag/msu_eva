@props([
    'title',
    'text',
    'icon' => null,
])

<div class="flex flex-col md:flex-row md:justify-between bg-white p-8 md:px-8 mb-6 rounded drop-shadow-md border-t border-l border-gray-100">
    <div class="text-left">
        <h2 class="text-black mb-2 font-medium text-2xl flex items-center">
            @if($icon)
            <i class="{{ $icon }} mr-2 text-3xl"></i>
            @endif
            {{ $title }}
        </h2>
        <p class="text-gray-700 text-lg font-normal">{{ $text }}</p>
    </div>

    @isset($action)
        <div class="flex items-center md:mt-0">
            {{ $action }}
        </div>
    @endisset
</div>



