@props(['active'])

@php
$classes = ($active ?? false)
            ? 'ml-2 block py-1 md:py-3 pl-1 align-middle text-gray-700 no-underline hover:text-gray-700 border-b-2 border-gray-800 hover:border-pink-500'
            : 'ml-2 block py-1 md:py-3 pl-1 align-middle text-gray-700 no-underline hover:text-gray-700 border-b-2';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
