@props(['color' => 'gray'])

@php
    $baseClasses = "inline-flex items-center justify-center p-2 rounded-lg transition-colors duration-200";
    
    $colorClasses = match($color) {
        'blue' => 'text-gray-400 hover:bg-blue-50 hover:text-blue-600',
        'yellow' => 'text-gray-400 hover:bg-yellow-50 hover:text-yellow-600',
        default => 'text-gray-400 hover:bg-gray-100 hover:text-gray-800',
    };
@endphp

<a {{ $attributes->merge(['class' => "$baseClasses $colorClasses"]) }}>
    {{ $slot }}
</a>