<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::middleware("auth:sanctum")->group(function () {
        Route::apiResource('brand', BrandController::class);
        Route::apiResource('product', ProductController::class);
        Route::apiResource('stock', StockController::class);
      
        Route::get('/logout', [ApiAuthController::class, 'logout']);
        Route::post("/logout-all", [ApiAuthController::class, 'logoutAll']);
        Route::get("/tokens", [ApiAuthController::class, 'tokens']);
        Route::post('/register-staff', [ApiAuthController::class, 'registerStaff']);
    });

    Route::post('/login', [ApiAuthController::class, 'login']);
    // Route::get('/login', [ApiAuthController::class, 'login']);
});
