@props(['invalid', 'label'])

@php
$classes = ($invalid ?? false)
            ? 'block mb-2 text-sm font-medium text-red-700 dark:text-red-500'
            : 'block mb-2 text-sm font-medium text-gray-900 dark:text-white'
@endphp

<label {{ $attributes->merge(['class' => $classes]) }}>
    {{ $label ?? $slot }}
</label>

  