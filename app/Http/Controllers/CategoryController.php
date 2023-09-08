<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return CategoryResource::collection($categories);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('isAdmin');

        $request->validate([
            'name' => 'required|unique:categories|min:3|max:15',
            'more_information' => 'nullable|string|min:5|max:1000'
        ]);
        $category = new Category;
        $category->name = $request->name;
        $category->more_information = $request->more_information;
        $category->save();
        return new CategoryResource($category);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        $products = $category->products()->paginate(15)->withQueryString();
        return ProductResource::collection($products);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize('isAdmin');

        $request->validate([
            'name' => 'required|min:3|max:15|unique:categories,id,' . $request->id,
            'more_information' => 'nullable|string|min:5|max:1000'
        ]);

        $category = Category::withCount('products')->findOrFail($id);
        $category->name = $request->name;
        $category->more_information = $request->more_information;
        $category->save();

        return new CategoryResource($category);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        Gate::authorize('isAdmin');
        //
    }
}
