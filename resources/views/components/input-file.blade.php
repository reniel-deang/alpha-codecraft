@props(['invalid'])

@php
$classes = ($invalid ?? false)
            ? 'w-full bg-red-50 border border-red-500 text-red-900 placeholder-red-700 text-sm rounded-lg focus:ring-red-500 dark:bg-gray-700 focus:border-red-500 block dark:text-red-500 dark:placeholder-red-500 dark:border-red-500'
            : 'block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400'
@endphp 

<input type="file" {{ $attributes->merge(['class' => $classes]) }}>