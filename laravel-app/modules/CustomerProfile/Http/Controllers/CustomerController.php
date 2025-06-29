<?php

namespace Modules\CustomerProfile\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\CustomerProfile\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Customer::all()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
        ]);
        $customer = Customer::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'tenant_id' => $request->header('X-Tenant-ID'),
        ]);
        return response()->json(['data' => $customer], 201);
    }

    public function show(Customer $customer)
    {
        return response()->json(['data' => $customer]);
    }
}
