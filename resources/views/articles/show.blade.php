@extends('layouts.app')

@section('title', $article->title . ' | Conduit')

@section('content')
    <!-- Article Hero -->
    <div class="bg-gray-800 text-white py-8">
        <div class="max-w-6xl mx-auto px-4">
            <h1 class="text-4xl font-bold mb-4">{{ $article->title }}</h1>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('profile', $article->author->username) }}" class="flex items-center space-x-2">
                        @if($article->author->image)
                            <img src="{{ $article->author->image }}" class="w-10 h-10 rounded-full" alt="{{ $article->author->username }}">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center">
                                <span class="text-white">{{ strtoupper(substr($article->author->username, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div>
                            <p class="font-medium">{{ $article->author->username }}</p>
                            <p class="text-gray-400 text-sm">{{ $article->created_at->format('F j, Y') }}</p>
                        </div>
                    </a>
                </div>
                
                <div class="flex items-center space-x-2">
                    @auth
                        @if(auth()->id() === $article->user_id)
                            <a href="{{ route('articles.edit', $article->slug) }}" 
                               class="border border-gray-400 text-gray-400 px-3 py-2 rounded text-sm hover:bg-gray-400 hover:text-gray-800 transition-colors duration-200">
                                <i class="ion-edit"></i> Edit Article
                            </a>
                            <form action="{{ route('articles.destroy', $article->slug) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Are you sure you want to delete this article?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="border border-red-400 text-red-400 px-3 py-2 rounded text-sm hover:bg-red-400 hover:text-white transition-colors duration-200">
                                    <i class="ion-trash-a"></i> Delete Article
                                </button>
                            </form>
                        @else
                            <button class="follow-btn border border-gray-400 text-gray-400 px-3 py-2 rounded text-sm hover:bg-gray-400 hover:text-gray-800 transition-colors duration-200"
                                    data-username="{{ $article->author->username }}">
                                <i class="ion-plus-round"></i>
                                {{ auth()->user()->isFollowing($article->author) ? 'Unfollow' : 'Follow' }} {{ $article->author->username }}
                            </button>
                            <button class="favorite-btn border border-green-500 text-green-500 px-3 py-2 rounded text-sm hover:bg-green-500 hover:text-white transition-colors duration-200"
                                    data-article-slug="{{ $article->slug }}">
                                <i class="ion-heart"></i>
                                {{ $article->favorited_by_user ? 'Unfavorite' : 'Favorite' }} Article ({{ $article->favorites_count }})
                            </button>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Article Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="prose prose-lg max-w-none mb-8">
            {!! nl2br(e($article->body)) !!}
        </div>
        
        <!-- Tags -->
        <div class="flex flex-wrap gap-2 mb-8">
            @foreach($article->tags as $tag)
                <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-sm">
                    {{ $tag->name }}
                </span>
            @endforeach
        </div>
        
        <hr class="my-8">
        
        <!-- Author Actions (repeated) -->
        <div class="text-center py-8">
            <div class="flex items-center justify-center space-x-4">
                <a href="{{ route('profile', $article->author->username) }}" class="flex items-center space-x-2">
                    @if($article->author->image)
                        <img src="{{ $article->author->image }}" class="w-10 h-10 rounded-full" alt="{{ $article->author->username }}">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-gray-600">{{ strtoupper(substr($article->author->username, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div>
                        <p class="font-medium">{{ $article->author->username }}</p>
                        @if($article->author->bio)
                            <p class="text-gray-500 text-sm">{{ $article->author->bio }}</p>
                        @endif
                    </div>
                </a>
            </div>
            
            @auth
                @if(auth()->id() !== $article->user_id)
                    <div class="flex items-center justify-center space-x-2 mt-4">
                        <button class="follow-btn border border-gray-400 text-gray-400 px-3 py-2 rounded text-sm hover:bg-gray-400 hover:text-gray-800 transition-colors duration-200"
                                data-username="{{ $article->author->username }}">
                            <i class="ion-plus-round"></i>
                            {{ auth()->user()->isFollowing($article->author) ? 'Unfollow' : 'Follow' }} {{ $article->author->username }}
                        </button>
                        <button class="favorite-btn border border-green-500 text-green-500 px-3 py-2 rounded text-sm hover:bg-green-500 hover:text-white transition-colors duration-200"
                                data-article-slug="{{ $article->slug }}">
                            <i class="ion-heart"></i>
                            {{ $article->favorited_by_user ? 'Unfavorite' : 'Favorite' }} Article ({{ $article->favorites_count }})
                        </button>
                    </div>
                @endif
            @endauth
        </div>

        <!-- Comments Section -->
        <div class="mt-8">
            @auth
                <!-- Add Comment Form -->
                <form action="{{ route('articles.comments.store', $article->slug) }}" method="POST" class="mb-8">
                    @csrf
                    <div class="border border-gray-300 rounded">
                        <textarea name="body" rows="3" 
                                  class="w-full p-4 border-0 resize-none focus:outline-none focus:ring-0" 
                                  placeholder="Write a comment..."></textarea>
                        <div class="bg-gray-100 px-4 py-3 flex justify-between items-center">
                            <div class="flex items-center space-x-2">
                                @if(auth()->user()->image)
                                    <img src="{{ auth()->user()->image }}" class="w-6 h-6 rounded-full" alt="{{ auth()->user()->username }}">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-gray-600 text-xs">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <span class="text-sm text-gray-600">{{ auth()->user()->username }}</span>
                            </div>
                            <button type="submit" 
                                    class="bg-green-500 text-white px-4 py-2 rounded text-sm hover:bg-green-600 transition-colors duration-200">
                                Post Comment
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="text-center py-4">
                    <p class="text-gray-500">
                        <a href="{{ route('login') }}" class="text-green-500 hover:underline">Sign in</a>
                        or
                        <a href="{{ route('register') }}" class="text-green-500 hover:underline">sign up</a>
                        to add comments on this article.
                    </p>
                </div>
            @endauth

            <!-- Comments List -->
            @forelse($article->comments->sortByDesc('created_at') as $comment)
                <div class="border border-gray-300 rounded mb-4">
                    <div class="p-4">
                        <p class="text-gray-800 mb-4">{{ $comment->body }}</p>
                    </div>
                    <div class="bg-gray-100 px-4 py-3 flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('profile', $comment->author->username) }}" class="flex items-center space-x-2">
                                @if($comment->author->image)
                                    <img src="{{ $comment->author->image }}" class="w-6 h-6 rounded-full" alt="{{ $comment->author->username }}">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-gray-600 text-xs">{{ strtoupper(substr($comment->author->username, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <span class="text-green-500 hover:underline text-sm">{{ $comment->author->username }}</span>
                            </a>
                            <span class="text-gray-500 text-sm">{{ $comment->created_at->format('F j, Y') }}</span>
                        </div>
                        @auth
                            @if(auth()->id() === $comment->user_id)
                                <form action="{{ route('articles.comments.destroy', [$article->slug, $comment->id]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500">
                                        <i class="ion-trash-a"></i>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <p class="text-gray-500">No comments yet.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
