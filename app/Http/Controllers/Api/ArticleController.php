<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;

class ArticleController extends ApiController
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request)
    {
        $query = Article::with(['user', 'tags']);

        // Filter by tag
        if ($request->has('tag')) {
            $query->byTag($request->tag);
        }

        // Filter by author
        if ($request->has('author')) {
            $query->byAuthor($request->author);
        }

        // Filter by favorited
        if ($request->has('favorited')) {
            $query->favoritedBy($request->favorited);
        }

        $articles = $query->latest()->paginate(20);

        return $this->respond([
            'articles' => $articles->map(function ($article) {
                return $this->transformArticle($article);
            }),
            'articlesCount' => $articles->total()
        ]);
    }

    /**
     * Store a newly created article.
     */
    public function store(Request $request)
    {
        $request->validate([
            'article.title' => 'required|string|max:255',
            'article.description' => 'required|string|max:255',
            'article.body' => 'required|string',
            'article.tagList' => 'sometimes|array',
        ]);

        $articleData = $request->input('article');
        
        $article = $request->user()->articles()->create([
            'title' => $articleData['title'],
            'description' => $articleData['description'],
            'body' => $articleData['body'],
        ]);

        // Handle tags
        if (isset($articleData['tagList']) && !empty($articleData['tagList'])) {
            $tags = collect($articleData['tagList'])->map(function ($tagName) {
                return Tag::firstOrCreate(['name' => $tagName]);
            });

            $article->tags()->attach($tags->pluck('id'));
        }

        $article->load(['user', 'tags']);

        return $this->respond([
            'article' => $this->transformArticle($article)
        ]);
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article)
    {
        $article->load(['user', 'tags']);

        return $this->respond([
            'article' => $this->transformArticle($article)
        ]);
    }

    /**
     * Update the specified article.
     */
    public function update(Request $request, Article $article)
    {
        if ($article->user_id !== $request->user()->id) {
            return $this->respondForbidden();
        }

        $request->validate([
            'article.title' => 'sometimes|string|max:255',
            'article.description' => 'sometimes|string|max:255',
            'article.body' => 'sometimes|string',
        ]);

        $articleData = $request->input('article');

        $article->update($articleData);
        $article->load(['user', 'tags']);

        return $this->respond([
            'article' => $this->transformArticle($article)
        ]);
    }

    public function favorite($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        
        $user = auth()->user();
        $favorited = $user->hasFavorited($article);
        
        if ($favorited) {
            $user->unfavorite($article);
            $favorited = false;
        } else {
            $user->favorite($article);
            $favorited = true;
        }
        
        $article->refresh();
        $article->load(['author', 'tags']);
        
        return response()->json([
            'article' => [
                'slug' => $article->slug,
                'title' => $article->title,
                'description' => $article->description,
                'body' => $article->body,
                'tagList' => $article->tags->pluck('name'),
                'createdAt' => $article->created_at->toISOString(),
                'updatedAt' => $article->updated_at->toISOString(),
                'favorited' => $favorited,
                'favoritesCount' => $article->favorites()->count(),
                'author' => [
                    'username' => $article->author->username,
                    'bio' => $article->author->bio,
                    'image' => $article->author->image,
                    'following' => auth()->check() ? auth()->user()->isFollowing($article->author) : false,
                ],
            ]
        ]);
    }

    public function feed()
    {
        $user = auth()->user();
        
        $articles = Article::with(['author', 'tags'])
            ->whereHas('author', function ($query) use ($user) {
                $query->whereIn('id', $user->following->pluck('id'));
            })
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
            
        $articlesData = $articles->map(function ($article) use ($user) {
            return [
                'slug' => $article->slug,
                'title' => $article->title,
                'description' => $article->description,
                'body' => $article->body,
                'tagList' => $article->tags->pluck('name'),
                'createdAt' => $article->created_at->toISOString(),
                'updatedAt' => $article->updated_at->toISOString(),
                'favorited' => $user->hasFavorited($article),
                'favoritesCount' => $article->favorites()->count(),
                'author' => [
                    'username' => $article->author->username,
                    'bio' => $article->author->bio,
                    'image' => $article->author->image,
                    'following' => $user->isFollowing($article->author),
                ],
            ];
        });

        return response()->json([
            'articles' => $articlesData,
            'articlesCount' => $articlesData->count(),
        ]);
    }

    /**
     * Get feed for authenticated user.
     */
    public function feed(Request $request)
    {
        $articles = $request->user()->feed()->paginate(20);

        return $this->respond([
            'articles' => $articles->map(function ($article) {
                return $this->transformArticle($article);
            }),
            'articlesCount' => $articles->total()
        ]);
    }

    /**
     * Favorite an article.
     */
    public function favorite(Request $request, Article $article)
    {
        $request->user()->favorite($article);
        $article->load(['user', 'tags']);

        return $this->respond([
            'article' => $this->transformArticle($article)
        ]);
    }

    /**
     * Unfavorite an article.
     */
    public function unfavorite(Request $request, Article $article)
    {
        $request->user()->unfavorite($article);
        $article->load(['user', 'tags']);

        return $this->respond([
            'article' => $this->transformArticle($article)
        ]);
    }

    /**
     * Transform article for API response.
     */
    private function transformArticle(Article $article)
    {
        return [
            'slug' => $article->slug,
            'title' => $article->title,
            'description' => $article->description,
            'body' => $article->body,
            'tagList' => $article->tagList,
            'createdAt' => $article->created_at->toISOString(),
            'updatedAt' => $article->updated_at->toISOString(),
            'favorited' => $article->favorited,
            'favoritesCount' => $article->favoritesCount,
            'author' => [
                'username' => $article->user->username,
                'bio' => $article->user->bio,
                'image' => $article->user->image,
                'following' => $article->user->following,
            ]
        ];
    }
}
