<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\SoldAccountController;
use App\Http\Controllers\BackgroundMusicController;
use App\Http\Controllers\PaymentMethodController;
use Illuminate\Support\Facades\Log;

Route::post('/test-upload', function (Request $request) {
    Log::info('Files received:', $request->allFiles());

    if ($request->hasFile('avatar')) {
        $file = $request->file('avatar');
        $path = $file->store('avatars', 'public');
        return response()->json([
            'success' => true,
            'filename' => $file->getClientOriginalName(),
            'path' => asset('storage/' . $path),
            'size' => $file->getSize(),
            'type' => $file->getMimeType(),
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'No file received',
        'allFiles' => $request->allFiles(),
    ]);
});

// ============================================
// PUBLIC ROUTES (No Authentication Required)
// ============================================

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/games', [GameController::class, 'index']);
Route::get('/games/{id}', [GameController::class, 'show']);

Route::get('/payment-methods/active', [PaymentMethodController::class, 'getActive']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::get('/banners', [BannerController::class, 'index']);

Route::get('/background-music/active', [BackgroundMusicController::class, 'getActive']);

// 🔥 FIX: Public sold accounts routes
Route::get('/sold-accounts', [SoldAccountController::class, 'index']);
Route::get('/sold-accounts/{id}', [SoldAccountController::class, 'show']);

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

// ============================================
// PROTECTED ROUTES (Authentication Required)
// ============================================

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/user/profile/update', [ProfileController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // USER ROUTES
    Route::middleware('role:user')->group(function () {
        Route::get('/user', function (Request $request) {
            return response()->json([
                'message' => 'Welcome User',
                'user' => $request->user()
            ]);
        });

        Route::get('/user/profile', function (Request $request) {
            return response()->json([
                'message' => 'User Profile Data',
                'profile' => $request->user()
            ]);
        });
    });

    // ADMIN ROUTES
    Route::middleware('role:admin')->group(function () {

        Route::get('/admin', function (Request $request) {
            return response()->json([
                'message' => 'Welcome Admin',
                'admin' => $request->user()
            ]);
        });

        Route::get('/admin/dashboard', function (Request $request) {
            return response()->json([
                'message' => 'Admin Dashboard Overview',
                'stats' => [
                    'total_users' => \App\Models\User::count(),
                    'total_admins' => \App\Models\User::where('role', 'admin')->count(),
                    'total_regular_users' => \App\Models\User::where('role', 'user')->count(),
                ],
                'admin' => $request->user()
            ]);
        });

        // GAMES MANAGEMENT
        Route::post('/games', [GameController::class, 'store']);
        Route::put('/games/{id}', [GameController::class, 'update']);
        Route::delete('/games/{id}', [GameController::class, 'destroy']);

        // PRODUCTS MANAGEMENT
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // BANNERS MANAGEMENT
        Route::get('/banners/all', [BannerController::class, 'all']);
        Route::post('/banners', [BannerController::class, 'store']);
        Route::put('/banners/{id}', [BannerController::class, 'update']);
        Route::delete('/banners/{id}', [BannerController::class, 'destroy']);

        // BACKGROUND MUSIC MANAGEMENT
        Route::get('/background-music', [BackgroundMusicController::class, 'index']);
        Route::post('/background-music', [BackgroundMusicController::class, 'store']);
        Route::put('/background-music/{id}', [BackgroundMusicController::class, 'update']);
        Route::delete('/background-music/{id}', [BackgroundMusicController::class, 'destroy']);
        Route::post('/background-music/{id}/activate', [BackgroundMusicController::class, 'setActive']);

        // SOLD ACCOUNTS MANAGEMENT
        Route::post('/sold-accounts', [SoldAccountController::class, 'store']);
        Route::put('/sold-accounts/{id}', [SoldAccountController::class, 'update']);
        Route::delete('/sold-accounts/{id}', [SoldAccountController::class, 'destroy']);

        // PAYMENT METHODS MANAGEMENT
        Route::get('/payment-methods', [PaymentMethodController::class, 'index']);
        Route::post('/payment-methods', [PaymentMethodController::class, 'store']);
        Route::put('/payment-methods/{id}', [PaymentMethodController::class, 'update']);
        Route::delete('/payment-methods/{id}', [PaymentMethodController::class, 'destroy']);
    });
});
