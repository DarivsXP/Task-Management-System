<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Task Management System') }}</title>

        <!-- Google Fonts Montserrat -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50">
            <div>
                <a href="/" class="flex items-center space-x-2">
                    <span class="text-2xl font-black text-indigo-600 tracking-tight">TASK<span class="text-slate-800">SYS</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-sm border border-slate-200 overflow-hidden sm:rounded-xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
