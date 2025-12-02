<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\StockDetailController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);

    Route::get('products/category/{categoryId}', [ProductController::class, 'getByCategory']);
    Route::apiResource('customers', CustomerController::class);
    
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('stock-details', StockDetailController::class);
    Route::apiResource('sales', SaleController::class);
    Route::apiResource('purchases', PurchaseController::class);

});
