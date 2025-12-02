<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Api\CustomerController as ApiCustomerController;
use App\Models\Customer;
use App\Http\Requests\CustomerRequest;

class CustomerController extends ApiCustomerController
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
        $customers = collect($data['data'] ?? [])->map(function ($item) {
            return (object) $item;
        });

        return view('masters.customers.index', [
            'customers' => $customers
        ]);
    }

    public function store(CustomerRequest $request)
    {
        $response = parent::store($request);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Validation failed');
            return redirect()->back()->withInput();
        }

        Alert::success('Success', 'Customer created successfully');
        return redirect()->route('customers.index');
    }

    public function show(Customer $customer)
    {
        $response = parent::show($customer);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Resource not found');
            return redirect()->back();
        }

        $data = $response->getData(true);
        $customerObject = (object) ($data['data'] ?? []);

        return view('masters.customers.show', [
            'customer' => $customerObject
        ]);
    }

    public function edit(Customer $customer)
    {
        $response = parent::show($customer);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Resource not found');
            return redirect()->back();
        }

        $data = $response->getData(true);
        $customerObject = (object) ($data['data'] ?? []);

        return view('masters.customers.edit', [
            'customer' => $customerObject
        ]);
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $response = parent::update($request, $customer);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Update failed');
            return redirect()->back()->withInput();
        }

        Alert::success('Success', 'Customer updated successfully');
        return redirect()->route('customers.index');
    }

    public function destroy(Customer $customer)
    {
        $response = parent::destroy($customer);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Delete failed');
            return redirect()->back();
        }

        Alert::success('Success', 'Customer deleted successfully');
        return redirect()->route('customers.index');
    }
}
