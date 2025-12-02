<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Api\SupplierController as ApiSupplierController;
use App\Models\Supplier;
use App\Http\Requests\SupplierRequest;

class SupplierController extends ApiSupplierController
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
        $suppliers = collect($data['data'] ?? [])->map(function ($item) {
            return (object) $item;
        });

        return view('masters.suppliers.index', [
            'suppliers' => $suppliers
        ]);
    }

    public function store(SupplierRequest $request)
    {
        $response = parent::store($request);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Validation failed');
            return redirect()->back()->withInput();
        }

        Alert::success('Success', 'Supplier created successfully');
        return redirect()->route('suppliers.index');
    }

    public function show(Supplier $supplier)
    {
        $response = parent::show($supplier);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Resource not found');
            return redirect()->back();
        }

        $data = $response->getData(true);
        $supplierObject = (object) ($data['data'] ?? []);

        return view('masters.suppliers.show', [
            'supplier' => $supplierObject
        ]);
    }

    public function edit(Supplier $supplier)
    {
        $response = parent::show($supplier);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Resource not found');
            return redirect()->back();
        }

        $data = $response->getData(true);
        $supplierObject = (object) ($data['data'] ?? []);

        return view('masters.suppliers.edit', [
            'supplier' => $supplierObject
        ]);
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $response = parent::update($request, $supplier);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Update failed');
            return redirect()->back()->withInput();
        }

        Alert::success('Success', 'Supplier updated successfully');
        return redirect()->route('suppliers.index');
    }

    public function destroy(Supplier $supplier)
    {
        $response = parent::destroy($supplier);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Delete failed');
            return redirect()->back();
        }

        Alert::success('Success', 'Supplier deleted successfully');
        return redirect()->route('suppliers.index');
    }
}
