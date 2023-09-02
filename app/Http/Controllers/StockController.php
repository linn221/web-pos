<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Http\Resources\BrandResource;
use App\Http\Resources\StockResource;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $stocks = Stock::latest("id")->paginate(20)->withQueryString();

        return StockResource::collection($stocks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockRequest $request)
    {
        Gate::authorize('isAdmin');

        $stock = Stock::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'more_information' => $request->more_information
        ]);

        // $totalStock = Stock::where('product_id', $request->product_id)->sum('quantity');

        // $product = Product::find($request->product_id);
        // $product->total_stock = $totalStock;
        // $product->save();

        return new StockResource($stock);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stock = Stock::find($id);
        if (is_null($stock)) {
            abort(404, 'stock not found');
        }

        return new StockResource($stock);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('isAdmin');

        $stock = Stock::find($id);
        if (is_null($stock)) {
            abort(404, 'stock not found');
        }

        $stock->delete();
        return response()->json([
            'message' => "stock has deleted"
        ], 204);
    }
}

