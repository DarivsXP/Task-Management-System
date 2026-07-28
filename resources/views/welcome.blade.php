<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Task Management System') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400..800&family=Inter:wght@300..700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-stone-50 text-stone-900 min-h-screen flex flex-col">
        <!-- Nav -->
        <header class="w-full max-w-6xl mx-auto px-6 py-5 flex items-center justify-between">
            <span class="font-display text-xl font-extrabold text-stone-900 tracking-tight">Task<span class="text-indigo-600">Sys</span></span>
            @if (Route::has('login'))
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('projects.index') }}" class="px-4 py-1.5 bg-stone-900 text-white rounded-lg font-medium text-sm hover:bg-stone-700 transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900 transition-colors">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-1.5 bg-stone-900 text-white rounded-lg font-medium text-sm hover:bg-stone-700 transition-colors">
                                Get Started
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Hero -->
        <main class="flex-1 flex items-center justify-center px-6 py-16">
            <div class="max-w-2xl text-center">
                <h1 class="font-display text-4xl md:text-5xl font-bold text-stone-900 leading-tight mb-5">
                    Manage projects.<br>Track tasks. Stay on top.
                </h1>
                <p class="text-stone-500 text-lg leading-relaxed mb-8">
                    A clean, straightforward task management system built with Laravel. Organize your work, filter by status, and search across projects with ease.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    @auth
                        <a href="{{ route('projects.index') }}" class="w-full sm:w-auto px-6 py-3 bg-stone-900 text-white rounded-lg font-medium hover:bg-stone-700 transition-colors">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 bg-stone-900 text-white rounded-lg font-medium hover:bg-stone-700 transition-colors">
                            Create Account
                        </a>
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3 border border-stone-300 text-stone-700 rounded-lg font-medium hover:bg-stone-100 transition-colors">
                            Log In
                        </a>
                    @endauth
                </div>
            </div>
        </main>

        <footer class="text-center py-5 text-xs text-stone-400">
            © {{ date('Y') }} TaskSys — Built with Laravel 11
        </footer>
    </body>
</html>
