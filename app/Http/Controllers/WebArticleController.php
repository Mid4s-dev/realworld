<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebArticleController extends Controller
{
    public function show($slug)
    {
        $article = Article::with(['author', 'tags', 'comments.author'])
            ->withCount('favorites')
            ->where('slug', $slug)
            ->firstOrFail();

        // Check if current user has favorited this article
        if (auth()->check()) {
            $article->favorited_by_user = auth()->user()->favorites()->where('article_id', $article->id)->exists();
        } else {
            $article->favorited_by_user = false;
        }

        return view('articles.show', compact('article'));
    }

    public function create()
    {
        return view('articles.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'body' => 'required|string',
            'tags' => 'nullable|string',
        ]);

        $article = Article::create([
            'title' => $request->title,
            'description' => $request->description,
            'body' => $request->body,
            'slug' => Str::slug($request->title) . '-' . Str::random(6),
            'user_id' => auth()->id(),
        ]);

        // Handle tags
        if ($request->tags) {
            $tagNames = array_map('trim', explode(',', $request->tags));
            $tags = [];
            
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tags[] = $tag->id;
                }
            }
            
            $article->tags()->sync($tags);
        }

        return redirect()->route('articles.show', $article->slug)
            ->with('success', 'Article created successfully!');
    }

    public function edit($slug)
    {
        $article = Article::with('tags')->where('slug', $slug)->firstOrFail();
        
        // Check if user owns this article
        if (auth()->id() !== $article->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('articles.form', compact('article'));
    }

    public function update(Request $request, $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        
        // Check if user owns this article
        if (auth()->id() !== $article->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'body' => 'required|string',
            'tags' => 'nullable|string',
        ]);

        $article->update([
            'title' => $request->title,
            'description' => $request->description,
            'body' => $request->body,
            'slug' => $article->title !== $request->title 
                ? Str::slug($request->title) . '-' . Str::random(6)
                : $article->slug,
        ]);

        // Handle tags
        if ($request->tags) {
            $tagNames = array_map('trim', explode(',', $request->tags));
            $tags = [];
            
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tags[] = $tag->id;
                }
            }
            
            $article->tags()->sync($tags);
        } else {
            $article->tags()->detach();
        }

        return redirect()->route('articles.show', $article->slug)
            ->with('success', 'Article updated successfully!');
    }

    public function destroy($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        
        // Check if user owns this article
        if (auth()->id() !== $article->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $article->delete();

        return redirect()->route('home')
            ->with('success', 'Article deleted successfully!');
    }

    public function storeComment(Request $request, $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        Comment::create([
            'body' => $request->body,
            'article_id' => $article->id,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Comment added successfully!');
    }

    public function destroyComment($slug, $commentId)
    {
        $comment = Comment::findOrFail($commentId);
        
        // Check if user owns this comment
        if (auth()->id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }
}
