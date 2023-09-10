<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockReportResource;
use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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
}
