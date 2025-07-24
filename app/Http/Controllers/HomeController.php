<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $articlesQuery = Article::with(['author', 'tags'])
            ->withCount('favorites')
            ->orderBy('created_at', 'desc');

        // Filter by tag if provided
        if ($request->has('tag')) {
            $articlesQuery->whereHas('tags', function ($query) use ($request) {
                $query->where('name', $request->tag);
            });
        }

        $articles = $articlesQuery->paginate(10);

        // Get popular tags
        $popularTags = Tag::has('articles')
            ->withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->limit(20)
            ->get();

        return view('home', compact('articles', 'popularTags'));
    }
}
