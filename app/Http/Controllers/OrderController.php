<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Jobs\OrderConfirmed;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'product_name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        // Create order
        $order = Order::create($request->all());

        // Dispatch job to send email
        OrderConfirmed::dispatch($order);

        return response()->json(['message' => 'Order placed successfully!'], 201);
    }
}
