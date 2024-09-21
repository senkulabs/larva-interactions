<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <title>{{ $title ?? 'Page Title' }}</title>
        @stack('styles')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <div class="container my-0 mx-auto p-4 md:p-0">
            {{ $slot }}
        </div>
        @stack('scripts')
    </body>
</html>
