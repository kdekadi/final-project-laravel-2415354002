<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        $customers = Customer::all();
        return response()->json([
            'success' => true,
            'message' => 'List Data Customers',
            'data'    => $customers
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_id' => ['required', 'string', 'unique:customers,customer_id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:customers,email'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['status'] = $data['status'] ?? true;

        $customer = Customer::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer Created Successfully',
            'data' => $customer
        ], 201);
    }

    public function show(Customer $customer): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Customer',
            'data'    => $customer
        ], 200);
    }

    public function update(Request $request, Customer $customer): JsonResponse
    {
        $data = $request->validate([
            'customer_id' => ['required', 'string', 'unique:customers,customer_id,' . $customer->id],
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['nullable', 'email', 'unique:customers,email,' . $customer->id],
            'phone'       => ['nullable', 'string'],
            'address'     => ['nullable', 'string'],
            'status'      => ['nullable', 'boolean'],
        ]);

        $customer->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer Updated Successfully',
            'data'    => $customer
        ], 200);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();
        return response()->json([
            'success' => true,
            'message' => 'Customer Deleted Successfully'
        ], 200);
    }
}