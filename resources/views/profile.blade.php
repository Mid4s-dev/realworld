@extends('layouts.app')

@section('title', 'Profile | Conduit')

@section('content')
    <div class="bg-gray-100 py-8">
        <div class="max-w-6xl mx-auto px-4 text-center">
            @if(auth()->user()->image)
                <img src="{{ auth()->user()->image }}" class="w-24 h-24 rounded-full mx-auto mb-4" alt="{{ auth()->user()->username }}">
            @else
                <div class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center mx-auto mb-4">
                    <span class="text-gray-600 text-2xl">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</span>
                </div>
            @endif
            
            <h1 class="text-3xl font-bold mb-2">{{ auth()->user()->username }}</h1>
            
            @if(auth()->user()->bio)
                <p class="text-gray-600 mb-4">{{ auth()->user()->bio }}</p>
            @endif
            
            <a href="{{ route('settings') }}" 
               class="inline-flex items-center space-x-2 border border-gray-400 text-gray-600 px-4 py-2 rounded text-sm hover:bg-gray-400 hover:text-white transition-colors duration-200">
                <i class="ion-gear-a"></i>
                <span>Edit Profile Settings</span>
            </a>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <a href="#" class="border-green-500 text-green-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    My Articles
                </a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Favorited Articles
                </a>
            </nav>
        </div>

        <!-- Articles will be loaded here -->
        <div class="text-center py-8">
            <p class="text-gray-500">Articles will be displayed here.</p>
        </div>
    </div>
@endsection
