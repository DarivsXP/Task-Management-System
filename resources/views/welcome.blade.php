<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Task Management System') }}</title>

        <!-- Google Fonts Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900 min-h-screen flex flex-col justify-between">
        <!-- Navigation Header -->
        <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="text-3xl font-black text-indigo-600 tracking-tight">TASK<span class="text-slate-900">SYS</span></span>
            </div>
            @if (Route::has('login'))
                <nav class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('projects.index') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 shadow-sm transition-all">
                            Go to Dashboard →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-slate-700 font-bold text-sm hover:text-indigo-600 transition-colors">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 shadow-sm transition-all">
                                Get Started
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Hero Section -->
        <main class="w-full max-w-5xl mx-auto px-6 py-16 text-center my-auto">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200 mb-6">
                Laravel 11 & PHP 8.3 Task Management System
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight leading-tight mb-6">
                Organize Your Projects & Tasks with Ease
            </h1>
            <p class="text-lg md:text-xl font-medium text-slate-600 max-w-2xl mx-auto mb-10 leading-relaxed">
                A simple, robust, and secure Task Management System built to organize projects, track task deadlines, filter by status, and manage team workflows seamlessly.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('projects.index') }}" class="w-full sm:w-auto px-8 py-4 bg-indigo-600 text-white rounded-xl font-bold text-base hover:bg-indigo-700 shadow-md transition-all">
                        View Projects Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-indigo-600 text-white rounded-xl font-bold text-base hover:bg-indigo-700 shadow-md transition-all">
                        Create Free Account
                    </a>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-slate-700 border border-slate-300 rounded-xl font-bold text-base hover:bg-slate-50 shadow-sm transition-all">
                        Log In to Demo Account
                    </a>
                @endauth
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full max-w-7xl mx-auto px-6 py-8 text-center text-xs font-semibold text-slate-400 border-t border-slate-200">
            <p>© {{ date('Y') }} Task Management System. Built with Laravel 11 & Plus Jakarta Sans.</p>
        </footer>
    </body>
</html>
