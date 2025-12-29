<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes - MozCommodities Mobile App
|--------------------------------------------------------------------------
|
| Base URL: /api/v1
|
| Authentication: Bearer Token (Laravel Sanctum)
| Header: Authorization: Bearer {token}
|
*/

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Public Routes (No Authentication Required)
    |--------------------------------------------------------------------------
    */

    // Authentication
    Route::prefix('auth')->group(function () {
        Route::post('/register/customer', [AuthController::class, 'registerCustomer']);
        Route::post('/register/supplier', [AuthController::class, 'registerSupplier']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);

    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/featured', [ProductController::class, 'featured']);
    Route::get('/products/latest', [ProductController::class, 'latest']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);

    // App info
    Route::get('/info', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'app_name' => 'MozCommodities',
                'app_version' => '1.0.0',
                'api_version' => 'v1',
                'currency' => 'MZN',
                'currency_symbol' => 'MT',
                'country' => 'Moçambique',
                'timezone' => 'Africa/Maputo',
                'payment_methods' => [
                    ['id' => 'mpesa', 'name' => 'M-Pesa', 'icon' => 'mpesa'],
                    ['id' => 'emola', 'name' => 'e-Mola', 'icon' => 'emola'],
                    ['id' => 'card', 'name' => 'Cartão de Crédito/Débito', 'icon' => 'card'],
                    ['id' => 'bank_transfer', 'name' => 'Transferência Bancária', 'icon' => 'bank'],
                ],
                'contact' => [
                    'phone' => '+258 84 000 0000',
                    'email' => 'info@mozcommodities.co.mz',
                    'whatsapp' => '+258 84 000 0000',
                ],
            ]
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | Protected Routes (Authentication Required)
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        // Authentication
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/profile', [AuthController::class, 'profile']);
            Route::put('/profile', [AuthController::class, 'updateProfile']);
            Route::put('/password', [AuthController::class, 'updatePassword']);
        });

        // Cart
        Route::prefix('cart')->group(function () {
            Route::get('/', [CartController::class, 'index']);
            Route::post('/', [CartController::class, 'store']);
            Route::put('/{id}', [CartController::class, 'update']);
            Route::delete('/{id}', [CartController::class, 'destroy']);
            Route::delete('/', [CartController::class, 'clear']);
        });

        // Orders
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::post('/', [OrderController::class, 'store']);
            Route::get('/{id}', [OrderController::class, 'show']);
            Route::post('/{id}/cancel', [OrderController::class, 'cancel']);
            Route::post('/{id}/confirm-payment', [OrderController::class, 'confirmStripePayment']);
        });

    });

});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Endpoint não encontrado. Verifique a URL e o método HTTP.'
    ], 404);
});
