<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Requests\SaleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\SaleResource;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $sales = Sale::with('customer')->paginate($perPage);

        if ($sales->count() > 0) {
            return SaleResource::collection($sales);
        }

        return response()->json(['message' => 'Data tidak tersedia'], 200);
    }

    public function store(SaleRequest $request)
    {
        DB::beginTransaction();

        try {

            Log::info('Incoming sale request', ['data' => $request->all()]);

            $sale = Sale::create($request->validated());

            foreach ($request->products as $item) {
                $sale->details()->create([
                    'product_id'  => $item['product_id'],
                    'qty'         => $item['qty'],
                    'price'       => $item['price'],
                    'total_price' => $item['qty'] * $item['price'],
                ]);

                $stock = Stock::where('product_id', $item['product_id'])
                        ->lockForUpdate()
                        ->first();
    
                if ($stock) {

                    if ($stock->total_stock < $item['qty']) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'Insufficient stock for product ID '.$item['product_id'],
                            'current_stock' => $stock->total_stock
                        ], 400);
                    }

                    $stock->decrement('total_stock', $item['qty']);
                } else {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Stock record not found for product ID '.$item['product_id']
                    ], 404);
                }
            }


            DB::commit();

            return response()->json([
                'message' => 'Sale created successfully',
                'data'    => new SaleResource($sale->load('details'))
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create sale',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function show(Sale $sale)
    {
        $sale->load('customer', 'details.product');

        return new SaleResource($sale);
    }

    public function destroy(Sale $sale)
    {
        DB::beginTransaction();

        try {
            $details = $sale->details()->get();

            foreach ($details as $detail) {
                $stock = Stock::where('product_id', $detail->product_id)
                            ->lockForUpdate()
                            ->first();

                if ($stock) {
                    $stock->increment('total_stock', $detail->qty);
                }
            }
            
            $sale->delete();

            DB::commit();

            return response()->json([
                'message' => 'Sale cancelled and stock restored successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Sale cancel failed: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to cancel sale',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

}
