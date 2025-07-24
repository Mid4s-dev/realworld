@extends('layouts.app')

@section('title', 'Settings | Conduit')

@section('content')
    <div class="max-w-md mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-center">Your Settings</h1>

        <form action="{{ route('settings') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <input type="url" 
                       name="image" 
                       value="{{ old('image', auth()->user()->image) }}"
                       placeholder="URL of profile picture"
                       class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500">
            </div>

            <div>
                <input type="text" 
                       name="username" 
                       value="{{ old('username', auth()->user()->username) }}"
                       placeholder="Your Name"
                       class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500"
                       required>
            </div>

            <div>
                <textarea name="bio" 
                          rows="8"
                          placeholder="Short bio about you"
                          class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500">{{ old('bio', auth()->user()->bio) }}</textarea>
            </div>

            <div>
                <input type="email" 
                       name="email" 
                       value="{{ old('email', auth()->user()->email) }}"
                       placeholder="Email"
                       class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500"
                       required>
            </div>

            <div>
                <input type="password" 
                       name="password" 
                       placeholder="New Password"
                       class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500">
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors duration-200">
                    Update Settings
                </button>
            </div>
        </form>

        <hr class="my-8">

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" 
                    class="w-full bg-red-500 text-white py-3 rounded-lg hover:bg-red-600 transition-colors duration-200">
                Or click here to logout.
            </button>
        </form>
    </div>
@endsection
