<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\OrderController;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use App\Http\Controllers\ProductController;

// Authenticated user route
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json($request->user());
});

// Login route to generate Passport token
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('MyAppToken')->accessToken;

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

// OAuth Token Route (For issuing tokens via Passport)
Route::post('/oauth/token', [AccessTokenController::class, 'issueToken'])
    ->middleware(['throttle']);

// Secure Orders Route
Route::middleware('auth:api')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
});

// Product Routes
Route::get('/products', [ProductController::class, 'index']); // ✅ Get all products
Route::post('/products', [ProductController::class, 'store']); // ✅ Create a new product
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);








