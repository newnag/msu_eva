@props([
    'id',
    'title' => null,
    'size' => 'max-w-md',
    // icon: ['bg' => 'bg-blue-100', 'svg' => '<svg ...>...</svg>']
    'icon' => null,
    'show' => false,
])
<div id="{{ $id }}"
     class="fixed inset-0 bg-opacity-50 backdrop-blur-md flex items-center justify-center z-50 {{ $show ? '' : 'hidden' }}">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full {{ $size }}">
        <div class="text-center">
            @if($icon)
                <div class="{{ $icon['bg'] ?? 'bg-blue-100' }} rounded-full p-4 mx-auto w-20 h-20 flex items-center justify-center mb-6">
                    {!! $icon['svg'] ?? '' !!}
                </div>
            @endif

            @if($title)
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $title }}</h3>
            @endif

            {{ $slot }}

            @isset($footer)
                <div class="flex justify-center space-x-4 mt-4">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
