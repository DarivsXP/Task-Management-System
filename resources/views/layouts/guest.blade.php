<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Task Management System') }}</title>

        <!-- Fonts: Syne + Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400..800&family=Inter:wght@300..700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-stone-900 antialiased bg-stone-50 min-h-screen flex flex-col">
        <div class="flex-1 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-stone-50">
            <div class="mb-6">
                <a href="/" class="flex items-center">
                    <span class="font-display text-2xl font-extrabold text-stone-900 tracking-tight">Task<span class="text-indigo-600">Sys</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-7 py-8 bg-white border border-stone-200 sm:rounded-xl shadow-sm">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
