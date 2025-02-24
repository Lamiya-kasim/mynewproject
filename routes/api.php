<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// Authenticated user route
Route::middleware([
    EnsureFrontendRequestsAreStateful::class,
    'auth:sanctum',
    \Illuminate\Http\Middleware\HandleCors::class, // Enable CORS
])->get('/user', function (Request $request) {
    return response()->json($request->user());
});

// Login route to generate token
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login successful',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ],
        'token' => $token,
    ], 200);
});




