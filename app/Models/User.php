<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'bio',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'username';
    }

    /**
     * Get all articles by the user.
     */
    public function articles()
    {
        return $this->hasMany(Article::class)->latest();
    }

    /**
     * Get all comments by the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    /**
     * Users that this user is following.
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id')->withTimestamps();
    }

    /**
     * Users that are following this user.
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id')->withTimestamps();
    }

    /**
     * The articles that belong to the user.
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'favorites', 'user_id', 'article_id');
    }

    /**
     * Follow a user.
     */
    public function follow(User $user)
    {
        if ($this->id !== $user->id && !$this->isFollowing($user)) {
            return $this->following()->attach($user);
        }
    }

    /**
     * Unfollow a user.
     */
    public function unfollow(User $user)
    {
        return $this->following()->detach($user);
    }

    /**
     * Check if this user is following another user.
     */
    public function isFollowing(User $user)
    {
        return $this->following()->where('followed_id', $user->id)->exists();
    }

    /**
     * Favorite an article.
     */
    public function favorite(Article $article)
    {
        if (!$this->hasFavorited($article)) {
            return $this->favorites()->attach($article);
        }
    }

    /**
     * Unfavorite an article.
     */
    public function unfavorite(Article $article)
    {
        return $this->favorites()->detach($article);
    }

    /**
     * Check if this user has favorited an article.
     */
    public function hasFavorited(Article $article)
    {
        return $this->favorites()->where('article_id', $article->id)->exists();
    }

    /**
     * Get the feed of articles from followed users.
     */
    public function feed()
    {
        $followingIds = $this->following()->pluck('followed_id')->toArray();
        
        return Article::with(['user', 'tags'])
            ->whereIn('user_id', $followingIds)
            ->latest();
    }

    /**
     * Check if the authenticated user is following this user.
     */
    public function getFollowingAttribute()
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->isFollowing($this);
    }
}
