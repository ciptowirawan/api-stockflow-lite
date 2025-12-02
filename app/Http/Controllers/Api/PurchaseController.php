<?php

namespace App\Http\Controllers\Api;

use App\Models\Stock;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseRequest;
use App\Http\Resources\PurchaseResource;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $purchases = Purchase::with('supplier', 'createdBy', 'details')->paginate($perPage);

        
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

                $stock = Stock::where('product_id', $item['product_id'])
                          ->lockForUpdate()
                          ->first();

                if ($stock) {
                    $stock->increment('total_stock', $item['qty']);
                } else {
                    Stock::create([
                        'product_id'  => $item['product_id'],
                        'total_stock' => $item['qty'],
                        'created_by'  => auth()->id(),
                        'updated_by'  => auth()->id(),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Purchase created successfully',
                'data'    => new PurchaseResource($purchase->load('details.product'))
            ], 201);

        } catch (\Exception $e) {
             \Log::error('create purchase Failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
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
        DB::beginTransaction();

        try {
            $details = $purchase->details()->get();

            foreach ($details as $detail) {
                $stock = Stock::where('product_id', $detail->product_id)
                            ->lockForUpdate()
                            ->first();

                if ($stock) {
                    $stock->decrement('total_stock', $detail->qty);
                }
            }

            $purchase->delete();

            DB::commit();

            return response()->json([
                'message' => 'Purchase cancelled and stock reverted successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Purchase cancel failed: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to cancel purchase',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

}
