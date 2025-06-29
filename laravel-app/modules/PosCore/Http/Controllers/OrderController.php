<?php

namespace Modules\PosCore\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\PosCore\Models\Order;
use Modules\PosCore\Models\Table;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Order::all()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:Modules\PosCore\Models\Table,id',
            'customer_id' => 'nullable|exists:Modules\CustomerProfile\Models\Customer,id',
            'items' => 'required|array',
            'items.*.menu_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        $total_amount = $this->calculateTotal($validated['items']);

        $order = Order::create([
            'tenant_id' => $request->header('X-Tenant-ID'),
            'table_id' => $validated['table_id'],
            'customer_id' => $validated['customer_id'] ?? null,
            'total_amount' => $total_amount,
            'status' => 'pending',
            'ordered_at' => now(),
        ]);

        return response()->json(['data' => $order], 201);
    }

    public function show(Order $order)
    {
        return response()->json(['data' => $order]);
    }

    private function calculateTotal(array $items): float
    {
        $menuPrices = [1 => 10.0, 2 => 15.0, 3 => 5.0];
        $total = 0.0;
        foreach ($items as $item) {
            $total += ($menuPrices[$item['menu_id']] ?? 0.0) * $item['quantity'];
        }
        return round($total, 2);
    }
}
