<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierResource;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $suppliers = Supplier::paginate($perPage);
        
        if ($suppliers->count() > 0) {
            return SupplierResource::collection($suppliers);
        } else {
            return response()->json(['message' => 'Data tidak tersedia'], 200);
        }
    }

    public function store(SupplierRequest $request)
    {
        $supplier = Supplier::create($request->validated());

        return response()->json([
            'message' => 'Supplier Created Successfully',
            'data'    => new SupplierResource($supplier)
        ], 201);
    }

    public function show(Supplier $supplier)
    {
        return new SupplierResource($supplier);
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());

        return response()->json([
            'message' => 'Supplier Updated Successfully',
            'data'    => new SupplierResource($supplier)
        ], 200);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response()->json([
            'message' => 'Supplier Deleted Successfully',
        ], 200);
    }
}
