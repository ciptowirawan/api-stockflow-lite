<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SaleResource;
use App\Http\Requests\SaleRequest;

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

            $sale = Sale::create($request->validated());

            foreach ($request->products as $item) {
                $sale->details()->create([
                    'product_id'  => $item['product_id'],
                    'qty'         => $item['qty'],
                    'price'       => $item['price'],
                    'total_price' => $item['qty'] * $item['price'],
                ]);
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
        $sale->delete();

        return response()->json([
            'message' => 'Sale deleted successfully'
        ], 200);
    }
}
