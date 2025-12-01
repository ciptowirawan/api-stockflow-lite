<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $customers = Customer::paginate($perPage);
        
        if ($customers->count() > 0) {
            return CustomerResource::collection($customers);
        } else {
            return response()->json(['message' => 'Data tidak tersedia'], 200);
        }
    }

    public function store(CustomerRequest $request)
    {
        $customer = Customer::create($request->validated());

        return response()->json([
            'message' => 'Customer Created Successfully',
            'data'    => new CustomerResource($customer)
        ], 201);
    }

    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return response()->json([
            'message' => 'Customer Updated Successfully',
            'data'    => new CustomerResource($customer)
        ], 200);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json([
            'message' => 'Customer Deleted Successfully',
        ], 200);
    }
}
