<?php

namespace App\Http\Controllers\Api;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\PurchaseResource;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $purchases = Purchase::with('supplier')->paginate($perPage);

        if ($purchases->count() > 0) {
            return PurchaseResource::collection($purchases);
        }

        return response()->json(['message' => 'Data tidak tersedia'], 200);
    }

    public function store(PurchaseRequest $request)
    {
        DB::beginTransaction();

        try {
            $purchase = Purchase::create($request->validated());

            foreach ($request->products as $item) {
                $purchase->details()->create([
                    'product_id'  => $item['product_id'],
                    'qty'         => $item['qty'],
                    'price'       => $item['price'],
                    'total_price' => $item['qty'] * $item['price'],
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Purchase created successfully',
                'data'    => new PurchaseResource($purchase->load('details.product'))
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create purchase',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('supplier', 'details.product');

        return new PurchaseResource($purchase);
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return response()->json([
            'message' => 'Purchase deleted successfully'
        ], 200);
    }
}
