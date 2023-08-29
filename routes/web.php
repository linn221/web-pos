<?php

use App\Models\VoucherRecord;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function() {


        VoucherRecord::insert([
            [
                'product_id' => 1,
                'quantity' => 1,
                'voucher_id' => 1,
                'cost' => 200,
                'created_at' => now(),
                'update_date' => now()
            ],
            [
                'product_id' => 1,
                'quantity' => 1,
                'voucher_id' => 1,
                'cost' => 200,
                'created_at' => now(),
                'update_date' => now()
            ]
        ]);
        // VoucherRecord::create($voucher_records);

});