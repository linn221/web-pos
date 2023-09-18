<?php

use App\Http\Controllers\SaleReportController;
use App\Models\DailySaleOverview;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

Route::get('products', [SaleReportController::class, 'bestSaleProducts']);
Route::get('/test', function () {
    $what = DB::table('vouchers')->whereBetween('vouchers.created_at', ['2023-09-11', '2023-09-15']);
    dd($what);
    // sql that got the job done
    // SELECT products.id as product_id, products.name AS product_name, brands.name AS brand_name, brands.id as brand_id, products_sale_count.sale_count as sold_amount FROM products INNER JOIN brands ON brands.id = products.brand_id INNER JOIN( SELECT product_id, SUM(voucher_records.quantity) AS sale_count FROM vouchers INNER JOIN voucher_records ON vouchers.id = voucher_records.voucher_id WHERE vouchers.created_at BETWEEN '2023-09-11' AND '2023-09-15' GROUP BY product_id ) AS products_sale_count ON products_sale_count.product_id = products.id ORDER BY products_sale_count.sale_count DESC; 
        $sql_product_sold_count = DB::table('vouchers')
            ->join('voucher_records', 'voucher_records.voucher_id', '=', 'vouchers.id')
            ->whereBetween('vouchers.created_at', ['2023-09-11', '2023-09-15'])
            ->selectRaw('voucher_records.product_id as product_id, sum(voucher_records.quantity) as sold_amount')
            ->groupBy('voucher_records.product_id')
            ->orderBy('sold_amount', 'desc');
    
        $brands_with_sale_count = DB::table('products')
            ->joinSub($sql_product_sold_count, 'product_count', 'products.id', '=', 'product_count.product_id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            // ->select(['pr'product_count.sold_amount as sold_amount', 'brands.id as brand_id', 'brands.name as brand_name'])
            ->selectRaw('brands.id as brand_id, sum(product_count.sold_amount) as sale_count')
            ->groupBy('products.brand_id')
            ->orderBy('sale_count', 'desc')
            ->get();
        // dd($sql);
        return $brands_with_sale_count;
    $sql_product_sold_count = DB::table('vouchers')
        ->join('voucher_records', 'voucher_records.voucher_id', '=', 'vouchers.id')
        ->whereBetween('vouchers.created_at', ['2023-09-11', '2023-09-15'])
        ->selectRaw('voucher_records.product_id as product_id, sum(voucher_records.quantity) as sold_amount')
        ->groupBy('voucher_records.product_id')
        ->orderBy('sold_amount', 'desc');

    $sql = DB::table('products')
        ->joinSub($sql_product_sold_count, 'product_count', 'products.id', '=', 'product_count.product_id')
        ->join('brands', 'products.brand_id', '=', 'brands.id')
        ->select(['products.id as product_id', 'products.name as product_name', 'product_count.sold_amount as sold_amount', 'brands.id as brand_id', 'brands.name as brand_name'])
        ->orderBy('sold_amount', 'desc')
        ->get();
    // dd($sql);
    return $sql;
    // $vouchers = Voucher::thisWeek()->with('voucher_records')->get();
    // return $vouchers;
    // dd($vouchers);
    // $vouchers = Voucher::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()])->first()->voucher_records()->dd();
    // return $vouchers;
});
