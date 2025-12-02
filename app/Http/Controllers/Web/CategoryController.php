<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\CategoryController as ApiCategoryController;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends ApiCategoryController
{
    public function index(Request $request)
    {
        return parent::index($request);
    }

    public function store(CategoryRequest $request)
    {
        return parent::store($request);
    }

    public function show(Category $category)
    {
        return parent::show($category);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        return parent::update($request, $category);
    }

    public function destroy(Category $category)
    {
        return parent::destroy($category);
    }
}
