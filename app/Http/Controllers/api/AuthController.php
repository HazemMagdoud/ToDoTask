<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\User;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
    }

    // Register user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $now = new DateTimeImmutable();
        // Set custom claims with DateTimeImmutable as strings
        $customClaims = [
            'sub' => $user->id,
            'iat' => $now->getTimestamp(),
            'nbf' => $now->getTimestamp(),
            'exp' => $now->modify('+60 minutes')->getTimestamp(), // 1-hour expiration
        ];

        // Generate token with custom claims
        $token = JWTAuth::customClaims($customClaims)->fromUser($user);
        return response()->json(['token' => $token], 201);
    }

    // Login a user and get a JWT token
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['token' => $token]);
    }

    // Logout the user
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    // Get the authenticated user
    public function user()
    {
        return response()->json(auth()->user());
    }
}
