<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockReportResource;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\returnSelf;

class StockReportController extends Controller
{
    public function productWithStockLevel(Request $request)
    {
        $products = Product::when($request->stock_level == 'in stock', function (Builder $query) {

            $query->where('total_stock', ">", 10);
        })
            ->when($request->stock_level == 'out of stock', function (Builder $query) {
                $query->where('total_stock', "<=", 0);
            })

            ->when($request->stock_level == 'low stock', function (Builder $query) {
                $query->whereBetween('total_stock', [0, 11]);
            })
            ->latest("id")
            ->paginate(10)->withQueryString();

        return StockReportResource::collection($products);
    }



    public function weeklyBestSellerBrands()
    {


        $startDate = Carbon::now()->startOfWeek(); // Start of the current week
        $endDate = Carbon::now()->endOfWeek();     // End of the current week


        $topProducts = Product::
            /*
        to retrieve all columns from the "products" table
         and, at the same time, calculate the total sum of the "quantity" column from the related "stocks" table,
          renaming the calculated sum as "total_entry_stock."
        */select('products.*', DB::raw('SUM(stocks.quantity) as total_entry_stock'))
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->whereBetween('stocks.created_at', [$startDate, $endDate])
            ->groupBy('products.id')
            // ->orderByDesc('total_entry_stock')
            ->take(5)
            ->get();





        $brands  = [];
        foreach ($topProducts as $topProduct) {
            $brands[] = $topProduct->brand;
        }

        return $brands;
    }
}
