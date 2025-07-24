<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'body',
        'slug',
        'user_id',
    ];

    protected $with = ['tags'];
    protected $appends = ['tagList', 'favorited', 'favoritesCount'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = static::generateSlug($article->title);
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title')) {
                $article->slug = static::generateSlug($article->title);
            }
        });
    }

    protected static function generateSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'article_id', 'user_id')->withTimestamps();
    }

    public function favorites()
    {
        return $this->favoritedBy();
    }

    public function getTagListAttribute()
    {
        return $this->tags->pluck('name')->toArray();
    }

    public function getFavoritedAttribute()
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->hasFavorited($this);
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favoritedBy()->count();
    }

    public function scopeByTag($query, $tag)
    {
        return $query->whereHas('tags', function ($query) use ($tag) {
            $query->where('name', $tag);
        });
    }

    public function scopeByAuthor($query, $username)
    {
        return $query->whereHas('user', function ($query) use ($username) {
            $query->where('username', $username);
        });
    }

    public function scopeFavoritedBy($query, $username)
    {
        return $query->whereHas('favoritedBy', function ($query) use ($username) {
            $query->where('username', $username);
        });
    }

    public function scopeWithAllRelations($query)
    {
        return $query->with([
            'user',
            'tags',
            'favoritedBy' => function ($query) {
                if (auth()->check()) {
                    $query->where('user_id', auth()->id());
                }
            }
        ])->withCount('favoritedBy as favorites_count');
    }
}
