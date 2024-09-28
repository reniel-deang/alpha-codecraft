<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="icon.svg" type="image/x-icon">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="antialiased bg-gray-50 dark:bg-gray-900">

        @include('layouts.navbar')

        @if(Auth::user()->user_type === 'Admin')
            @include('layouts.sidebar')
        @endauth
        
        {{ $slot }}

        @stack('scripts')
    </body>
</html>
