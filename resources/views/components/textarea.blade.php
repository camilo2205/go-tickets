@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'text-xs shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50',
    'rows' => '4', 'style' => 'resize:none;overflow:auto;'
]) !!}>{{ $slot }}</textarea>
