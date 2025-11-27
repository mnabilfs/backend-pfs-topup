<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BannerController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public routes untuk user biasa
Route::get('/games', [GameController::class, 'index']);
Route::get('/games/{id}', [GameController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/banners', [BannerController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {

    // LOGOUT SEMUA ROLE
    Route::post('/logout', [AuthController::class, 'logout']);

    // 👤 USER AREA
    Route::middleware('role:user')->group(function () {

        // GET /api/user
        Route::get('/user', function (Request $request) {
            return response()->json([
                'message' => 'Welcome User',
                'user' => $request->user()
            ]);
        });

        // 🧍‍♂️ GET /api/user/profile
        Route::get('/user/profile', function (Request $request) {
            return response()->json([
                'message' => 'User Profile Data',
                'profile' => $request->user()
            ]);
        });
    });

    // 👑 ADMIN AREA
    Route::middleware('role:admin')->group(function () {

        // GET /api/admin
        Route::get('/admin', function (Request $request) {
            return response()->json([
                'message' => 'Welcome Admin',
                'admin' => $request->user()
            ]);
        });

        // 🧭 GET /api/admin/dashboard
        Route::get('/admin/dashboard', function (Request $request) {
            // Contoh data dashboard — nanti bisa kamu ubah ambil dari DB
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

        // 🎮 GAMES MANAGEMENT (Admin Only)
        Route::post('/games', [GameController::class, 'store']);
        Route::put('/games/{id}', [GameController::class, 'update']);
        Route::delete('/games/{id}', [GameController::class, 'destroy']);

        // 💎 PRODUCTS MANAGEMENT (Admin Only)
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // 🎨 BANNERS MANAGEMENT (Admin Only)
        Route::get('/banners/all', [BannerController::class, 'all']);
        Route::post('/banners', [BannerController::class, 'store']);
        Route::put('/banners/{id}', [BannerController::class, 'update']);
        Route::delete('/banners/{id}', [BannerController::class, 'destroy']);
    });
});

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});
