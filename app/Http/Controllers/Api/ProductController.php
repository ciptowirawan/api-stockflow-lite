<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $products = Product::with('category', 'createdBy', 'updatedBy')->paginate($perPage);
        
        if ($products->count() > 0) {
            return ProductResource::collection($products);
        } else {
            return response()->json(['message' => 'Data tidak tersedia'], 200);
        }
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated());

        return response()->json([
            'message' => 'Product Created Successfully',
            'data'    => new ProductResource($product)
        ], 201);
    }

    public function show(Product $product)
    {
        $product->load('category', 'createdBy', 'updatedBy');
        return new ProductResource($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return response()->json([
            'message' => 'Product Updated Successfully',
            'data'    => new ProductResource($product)
        ], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product Deleted Successfully',
        ], 200);
    }

    public function getByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->get();

        if ($products->count() > 0) {
            return ProductResource::collection($products);
        }

        return response()->json(['message' => 'Data tidak tersedia'], 200);
    }
}
