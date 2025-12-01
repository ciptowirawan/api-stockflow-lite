<?php

namespace App\Http\Controllers\Api;

use App\Models\Stock;
use App\Models\StockDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\StockResource;
use App\Http\Requests\StockDetailRequest;
use App\Http\Resources\StockDetailResource;

class StockDetailController extends Controller
{
    public function index()
    {

        $perPage = $request->get('per_page', 10);
        $details   = StockDetail::paginate($perPage);

        if ($details->count() > 0) {
            return StockDetailResource::collection($details);
        }

        return response()->json(['message' => 'Data tidak tersedia'], 200);
    }

    public function store(StockDetailRequest $request)
    {
        try {
            DB::beginTransaction();

            $stock = Stock::where('product_id', $request->product_id)
                ->lockForUpdate()
                ->first();

            if (!$stock) {
                $stock = Stock::create([
                    'product_id'  => $request->product_id,
                    'total_stock' => 0,
                ]);
            }

            if ($request->quantity < 0 && $stock->total_stock < abs($request->quantity)) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Insufficient stock. Current stock is ' . $stock->total_stock,
                ], 400);
            }

            $stock->increment('total_stock', $request->quantity);
            $stock->refresh();

            $detail = StockDetail::create([
                'product_id'  => $request->product_id,
                'quantity'    => $request->quantity,
                'stock_after' => $stock->total_stock,
                'type'        => $request->type,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Stock movement recorded successfully',
                'data'    => new StockDetailResource($detail),
                'stock'   => new StockResource($stock),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to process stock movement.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
