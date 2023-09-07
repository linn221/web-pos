<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
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

Route::prefix('v1')->group(function () {

    Route::middleware("auth:sanctum")->group(function () {
        
        // photo
        Route::apiResource('photo', PhotoController::class)->except(['show', 'update']);

        // brand
        Route::apiResource('brand', BrandController::class);

        // category
        Route::apiResource('category', CategoryController::class)->except(['destroy']);

        // product
        Route::apiResource('product', ProductController::class);

        // stock
        Route::apiResource('stock', StockController::class)->except(['update']);

        // voucher (sales)
        Route::controller(VoucherController::class)
        ->prefix('/voucher')->group(function () {
            Route::post('/restore/{id}', 'restore');
            Route::get('/show-trash', 'showTrash');

            Route::middleware('can:isAdmin')->group(function () {
                Route::post('/force-delete/{id}', 'forceDelete');
                Route::post('/empty-bin', 'emptyBin');
                Route::post('/recycle-bin', 'recycleBin');
            });
        });
        Route::apiResource('voucher', VoucherController::class)->except(['update']);

        // finance
        Route::controller(FinanceController::class)
        ->prefix('/finance')->group(function () {
            Route::post('/close-sale', 'closeSale');
            Route::get('/daily/{date}', 'daily');
            Route::get('/sale-close-check', 'checkSaleClose');
        });

        // user account
        Route::controller(UserController::class)
        ->group(function() {
            Route::post('/change-password', 'changePassword');
            Route::post('/change-staff-password', 'modifyPassword');
            Route::post('/ban-user/{id}', 'ban');
        });
        Route::apiResource('user', UserController::class)->except(['destroy']);

        // auth
        Route::controller(ApiAuthController::class)
        ->group(function() {
            Route::post('/logout', 'logout');
            Route::post("/logout-all", 'logoutAll');
            Route::get("/tokens", 'tokens');
        });
    });

    Route::post('/login', [ApiAuthController::class, 'login'])->name('login');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});