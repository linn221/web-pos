<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandDetailResource;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::latest('id')->paginate(15)->withQueryString();
        // return response()->json($brands);
        return BrandResource::collection($brands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {


        $brand = Brand::create([
            'name' => $request->name,
            "company" => $request->company,
            'information' => $request->information,
            'user_id' => Auth::id()
        ]);


        // return response()->json($brand);
        return new BrandDetailResource($brand);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::find($id);

        if (is_null($brand)) {
            return response()->json([
                // "success" => false,
                "message" => "Contact not found",

            ], 404);
        }

        // return response()->json($brand);
        return new BrandDetailResource($brand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, string $id)
    {


        $brand = Brand::find($id);

        if (is_null($brand)) {
            return response()->json([
                // "success" => false,
                "message" => "Contact not found",

            ], 404);
        }

        $brand->update([
            'name' => $request->name,
            "company" => $request->company,
            'information' => $request->information,
            'user_id' => Auth::id()
        ]);


        // return response()->json($brand);
        return new BrandDetailResource($brand);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);

        if (is_null($brand)) {
            return response()->json([
                // "success" => false,
                "message" => "Contact not found",

            ], 404);
        }

        $brand->delete();

        return response()->json([
            'message' => 'brand has deleted'
        ], 204);
    }
}
