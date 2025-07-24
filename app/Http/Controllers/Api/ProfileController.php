<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        return response()->json([
            'profile' => [
                'username' => $user->username,
                'bio' => $user->bio,
                'image' => $user->image,
                'following' => auth()->check() ? auth()->user()->isFollowing($user) : false,
            ]
        ]);
    }

    public function follow($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $currentUser = auth()->user();
        
        if ($currentUser->id === $user->id) {
            return response()->json(['message' => 'You cannot follow yourself'], 422);
        }
        
        $following = $currentUser->isFollowing($user);
        
        if ($following) {
            $currentUser->unfollow($user);
            $following = false;
        } else {
            $currentUser->follow($user);
            $following = true;
        }
        
        return response()->json([
            'profile' => [
                'username' => $user->username,
                'bio' => $user->bio,
                'image' => $user->image,
                'following' => $following,
            ]
        ]);
    }
}
