@extends('layouts.app')

@section('title', 'Home | Conduit')

@section('content')
    <!-- Hero Banner -->
    <div class="bg-green-500 text-white py-8">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-2">conduit</h1>
            <p class="text-xl opacity-90">A place to share your knowledge.</p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Articles Feed -->
            <div class="lg:col-span-3">
                <!-- Feed Toggle -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm feed-toggle" data-feed="global">
                            Global Feed
                        </a>
                        @auth
                            <a href="#" class="border-green-500 text-green-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm feed-toggle active" data-feed="your">
                                Your Feed
                            </a>
                        @endauth
                    </nav>
                </div>

                <!-- Articles List -->
                <div id="articles-container">
                    @forelse($articles as $article)
                        <article class="border-b border-gray-200 py-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('profile', $article->author->username) }}">
                                        @if($article->author->image)
                                            <img src="{{ $article->author->image }}" class="w-8 h-8 rounded-full" alt="{{ $article->author->username }}">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-gray-600 text-sm">{{ strtoupper(substr($article->author->username, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    </a>
                                    <div>
                                        <a href="{{ route('profile', $article->author->username) }}" class="text-green-500 hover:underline font-medium">
                                            {{ $article->author->username }}
                                        </a>
                                        <p class="text-gray-500 text-sm">{{ $article->created_at->format('F j, Y') }}</p>
                                    </div>
                                </div>
                                <button class="favorite-btn border border-green-500 text-green-500 px-3 py-1 rounded text-sm hover:bg-green-500 hover:text-white transition-colors duration-200"
                                        data-article-slug="{{ $article->slug }}">
                                    <i class="ion-heart"></i> {{ $article->favorites_count }}
                                </button>
                            </div>
                            
                            <div class="mb-4">
                                <h2 class="text-xl font-semibold mb-2">
                                    <a href="{{ route('articles.show', $article->slug) }}" class="text-gray-900 hover:text-gray-600">
                                        {{ $article->title }}
                                    </a>
                                </h2>
                                <p class="text-gray-600 mb-3">{{ $article->description }}</p>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <a href="{{ route('articles.show', $article->slug) }}" class="text-gray-500 text-sm hover:underline">
                                    Read more...
                                </a>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($article->tags as $tag)
                                        <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500">No articles are here... yet.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($articles->hasPages())
                    <div class="mt-8">
                        {{ $articles->links() }}
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-gray-100 rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-4">Popular Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($popularTags as $tag)
                            <a href="{{ route('home', ['tag' => $tag->name]) }}" 
                               class="bg-gray-600 text-white px-2 py-1 rounded text-sm hover:bg-gray-700 transition-colors duration-200">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
