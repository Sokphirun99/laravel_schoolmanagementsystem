@props(['type' => 'info', 'message' => null, 'title' => null, 'icon' => null])

@php
$colors = [
    'info' => 'bg-blue-100 border-blue-500 text-blue-700',
    'success' => 'bg-green-100 border-green-500 text-green-700',
    'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700',
    'danger' => 'bg-red-100 border-red-500 text-red-700',
];
@endphp

<div class="border-l-4 p-4 {{ $colors[$type] ?? $colors['info'] }}" role="alert">
    <p class="font-bold">
        @if($icon)
            <i class="{{ $icon }} mr-1"></i>
        @endif
        {{ $title ?? ucfirst($type) }}
    </p>
    @php $content = $message ?? ($slot->isNotEmpty() ? $slot : null); @endphp
    @if($content)
        <p>{{ $content }}</p>
    @endif
    
</div>
