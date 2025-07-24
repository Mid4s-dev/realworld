<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Conduit')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-white">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-green-500">conduit</a>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">Home</a>
                    @auth
                        <a href="{{ route('articles.create') }}" class="text-gray-600 hover:text-gray-900">
                            <i class="ion-compose"></i>&nbsp;New Article
                        </a>
                        <a href="{{ route('settings') }}" class="text-gray-600 hover:text-gray-900">
                            <i class="ion-gear-a"></i>&nbsp;Settings
                        </a>
                        <a href="{{ route('profile', auth()->user()->username) }}" class="text-gray-600 hover:text-gray-900">
                            @if(auth()->user()->image)
                                <img src="{{ auth()->user()->image }}" class="inline w-6 h-6 rounded-full mr-1" alt="Profile">
                            @endif
                            {{ auth()->user()->username }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Sign in</a>
                        <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-900">Sign up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 border-t border-gray-200 mt-16">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="flex justify-center">
                <a href="{{ route('home') }}" class="text-green-500 font-medium">conduit</a>
                <span class="mx-2 text-gray-500">-</span>
                <span class="text-gray-500">An interactive learning project from</span>
                <a href="https://thinkster.io" class="text-green-500 hover:underline ml-1">Thinkster</a>
                <span class="mx-2 text-gray-500">-</span>
                <a href="https://github.com/gothinkster/realworld" class="text-green-500 hover:underline">Fork on GitHub</a>
            </div>
        </div>
    </footer>
</body>
</html>
