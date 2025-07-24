@extends('layouts.app')

@section('title', (isset($article) ? 'Edit' : 'New') . ' Article | Conduit')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">{{ isset($article) ? 'Edit' : 'New' }} Article</h1>

        <form action="{{ isset($article) ? route('articles.update', $article->slug) : route('articles.store') }}" method="POST">
            @csrf
            @if(isset($article))
                @method('PUT')
            @endif

            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <input type="text" 
                           name="title" 
                           value="{{ old('title', $article->title ?? '') }}"
                           placeholder="Article Title"
                           class="w-full p-4 border border-gray-300 rounded-lg text-xl focus:outline-none focus:border-green-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <input type="text" 
                           name="description" 
                           value="{{ old('description', $article->description ?? '') }}"
                           placeholder="What's this article about?"
                           class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 @error('description') border-red-500 @enderror">
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Body -->
                <div>
                    <textarea name="body" 
                              rows="8"
                              placeholder="Write your article (in markdown)"
                              class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 @error('body') border-red-500 @enderror">{{ old('body', $article->body ?? '') }}</textarea>
                    @error('body')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags -->
                <div>
                    <input type="text" 
                           name="tags" 
                           value="{{ old('tags', isset($article) ? $article->tags->pluck('name')->implode(', ') : '') }}"
                           placeholder="Enter tags (separated by commas)"
                           class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 @error('tags') border-red-500 @enderror">
                    @error('tags')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Separate tags with commas</p>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors duration-200">
                        {{ isset($article) ? 'Update' : 'Publish' }} Article
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
