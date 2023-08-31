<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\DailySaleOverviewController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use App\Models\DailySaleOverview;
use App\Models\Photo;
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

        Route::get('voucher/restore/{id}', [VoucherController::class, 'restore']);

        Route::post('photo/multiple-delete', [PhotoController::class, 'multipleDestroy']);
        Route::apiResource('photo', PhotoController::class);
        Route::apiResource('brand', BrandController::class);
        Route::apiResource('product', ProductController::class);
        Route::apiResource('stock', StockController::class)->except(['update']);
        Route::apiResource('voucher', VoucherController::class)->except(['update', 'destroy']);
        Route::apiResource('user', UserController::class)->except(['destroy']);
        Route::get('/daily-sale-overview/{date}', [FinanceController::class, 'daily']);
        Route::post('/sale-close', [FinanceController::class, 'saleClose']);

        Route::get('/logout', [ApiAuthController::class, 'logout']);
        Route::post("/logout-all", [ApiAuthController::class, 'logoutAll']);
        Route::get("/tokens", [ApiAuthController::class, 'tokens']);
        Route::post('/ban-user/{id}', [UserController::class, 'ban']);
        // Route::post('/register-staff', [ApiAuthController::class, 'registerStaff']);

        Route::post('/change-password', [UserController::class, 'changePassword']);
        Route::post('/change-staff-password', [UserController::class, 'modifyPassword']);
    });

    Route::post('/login', [ApiAuthController::class, 'login'])->name('login');
});
