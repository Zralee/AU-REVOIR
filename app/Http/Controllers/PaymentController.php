<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(Request $request)
    {
        $ordersId = $request->get('orders');
        $snapToken = $request->get('snapToken');

        $orders = Order::whereIn('id', $ordersId)->get();

        // \Log::info('Order Data:', $orders);
        // \Log::info('Snap Token:', ['snapToken' => $snapToken]);

        return view('payment.gateway', compact('orders', 'snapToken'));
    }
}