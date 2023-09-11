<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'brand'])->latest("id")->paginate(15)->withQueryString();

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        Gate::authorize('isAdmin');
        $product = Product::create([
            'name' => $request->name,
            'actual_price' => $request->actual_price,
            'sale_price' => $request->sale_price,
            'total_stock' => 0,
            'unit' => $request->unit,
            'more_information' => $request->more_information,
            'photo' => $request->photo,
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id
        ]);

        return new ProductDetailResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            abort(404, 'product not found');
        }

        return new ProductDetailResource($product);

        // return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        Gate::authorize('isAdmin');
        $product = Product::find($id);
        if (is_null($product)) {
            abort(404, 'product not found');
        }

        $this->authorize('update', $product);

        $product->update([
            'name' => $request->name,
            'actual_price' => $request->actual_price,
            'sale_price' => $request->sale_price,
            // 'total_stock' => $request->total_stock,
            'unit' => $request->unit,
            'more_information' => $request->more_information,
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'photo' => $request->photo,
        ]);

        // return response()->json($product);
        return new ProductDetailResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('isAdmin');
        $product = Product::find($id);
        if (is_null($product)) {
            abort(404, 'product not found');
        }

        $this->authorize('delete', $product);

        $product->delete();

        return response()->json([
            'message' => 'product has been deleted',
        ], 204);
    }
}
