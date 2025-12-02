<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Models\Product;
use App\Http\Requests\ProductRequest;

class ProductController extends ApiProductController
{
    public function index(Request $request)
    {
        $response = parent::index($request);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Something went wrong');
            return redirect()->back();
        }

        $data = $response->getData(true);
        $products = collect($data['data'] ?? [])->map(function ($item) {
            return (object) $item;
        });

        return view('masters.products.index', [
            'products' => $products
        ]);
    }

    public function store(ProductRequest $request)
    {
        $response = parent::store($request);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Validation failed');
            return redirect()->back()->withInput();
        }

        Alert::success('Success', 'Product created successfully');
        return redirect()->route('products.index');
    }

    public function show(Product $product)
    {
        $response = parent::show($product);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Resource not found');
            return redirect()->back();
        }

        $data = $response->getData(true);
        $productObject = (object) ($data['data'] ?? []);

        return view('masters.products.show', [
            'product' => $productObject
        ]);
    }

    public function edit(Product $product)
    {
        $response = parent::show($product);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Resource not found');
            return redirect()->back();
        }

        $data = $response->getData(true);
        $productObject = (object) ($data['data'] ?? []);

        return view('masters.products.edit', [
            'product' => $productObject
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $response = parent::update($request, $product);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Update failed');
            return redirect()->back()->withInput();
        }

        Alert::success('Success', 'Product updated successfully');
        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $response = parent::destroy($product);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Delete failed');
            return redirect()->back();
        }

        Alert::success('Success', 'Product deleted successfully');
        return redirect()->route('products.index');
    }
}
