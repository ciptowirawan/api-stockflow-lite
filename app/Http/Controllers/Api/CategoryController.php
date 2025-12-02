<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $categories = Category::with('createdBy', 'updatedBy')->paginate($perPage);
        
        if ($categories->count() > 0) {
            return CategoryResource::collection($categories);
        } else {
            return response()->json(['message' => 'Data tidak tersedia'], 200);
        }
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return response()->json([
            'message' => 'Category Created Successfully',
            'data'    => new CategoryResource($category)
        ], 201);
    }

    public function show(Category $category)
    {
        $category->load('createdBy', 'updatedBy');
        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return response()->json([
            'message' => 'Category Updated Successfully',
            'data'    => new CategoryResource($category)
        ], 200);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Category Deleted Successfully',
        ], 200);
    }
}
