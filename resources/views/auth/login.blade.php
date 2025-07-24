@extends('layouts.app')

@section('title', 'Sign In | Conduit')

@section('content')
    <div class="max-w-md mx-auto px-4 py-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">Sign in</h1>
            <p class="text-gray-600">
                <a href="{{ route('register') }}" class="text-green-500 hover:underline">Need an account?</a>
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <input type="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       placeholder="Email"
                       class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 @error('email') border-red-500 @enderror"
                       required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <input type="password" 
                       name="password" 
                       placeholder="Password"
                       class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 @error('password') border-red-500 @enderror"
                       required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
            </div>

            <button type="submit" 
                    class="w-full bg-green-500 text-white py-3 rounded-lg hover:bg-green-600 transition-colors duration-200">
                Sign in
            </button>
        </form>
    </div>
@endsection
