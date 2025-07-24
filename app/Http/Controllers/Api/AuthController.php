<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends ApiController
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'user.username' => 'required|string|max:255|unique:users,username',
            'user.email' => 'required|email|unique:users,email',
            'user.password' => 'required|string|min:6',
        ]);

        $userData = $request->input('user');

        $user = User::create([
            'username' => $userData['username'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->respond([
            'user' => [
                'email' => $user->email,
                'token' => $token,
                'username' => $user->username,
                'bio' => $user->bio,
                'image' => $user->image,
            ]
        ]);
    }

    /**
     * Login user.
     */
    public function login(Request $request)
    {
        $request->validate([
            'user.email' => 'required|email',
            'user.password' => 'required|string',
        ]);

        $userData = $request->input('user');

        if (!Auth::attempt(['email' => $userData['email'], 'password' => $userData['password']])) {
            return $this->respond([
                'errors' => [
                    'email or password' => ['is invalid'],
                ]
            ], 422);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->respond([
            'user' => [
                'email' => $user->email,
                'token' => $token,
                'username' => $user->username,
                'bio' => $user->bio,
                'image' => $user->image,
            ]
        ]);
    }

    /**
     * Get current user.
     */
    public function user(Request $request)
    {
        $user = $request->user();

        return $this->respond([
            'user' => [
                'email' => $user->email,
                'token' => $request->bearerToken(),
                'username' => $user->username,
                'bio' => $user->bio,
                'image' => $user->image,
            ]
        ]);
    }

    /**
     * Update current user.
     */
    public function updateUser(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'user.username' => 'sometimes|string|max:255|unique:users,username,' . $user->id,
            'user.email' => 'sometimes|email|unique:users,email,' . $user->id,
            'user.password' => 'sometimes|string|min:6',
            'user.bio' => 'sometimes|nullable|string',
            'user.image' => 'sometimes|nullable|url',
        ]);

        $userData = $request->input('user');

        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        $user->update($userData);

        return $this->respond([
            'user' => [
                'email' => $user->email,
                'token' => $request->bearerToken(),
                'username' => $user->username,
                'bio' => $user->bio,
                'image' => $user->image,
            ]
        ]);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->respondSuccess('Successfully logged out');
    }
}
