<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function show(Request $request)
    {
    $orderIds = $request->input('orders');
    $orders = Order::whereIn('id', $orderIds)->get();
    $snapToken = $request->input('snapToken');
    $courierFee = $request->input('courierFee');
    $message = $request->input('message');
    $orderId = $request->input('orderId'); // Ambil orderId dari request

    return view('payment.gateway', compact('orders', 'snapToken', 'courierFee', 'message', 'orderId'));
    }

    public function paymentSuccess(Request $request)
{
    // Ambil data pesanan dari request atau sesi
    $orderId = $request->session()->get('orderId');
    $productName = $request->session()->get('productName');
    $quantity = $request->session()->get('quantity');
    $totalAmount = $request->session()->get('totalAmount');

    // Mengirim data ke view
    return view('payment.payment_success', compact('orderId', 'productName', 'quantity', 'totalAmount'));
}

public function updatePaymentStatus(Request $request)
{
    $orderId = $request->order_id;
    $paymentStatus = $request->payment_status;

    // Misalnya, menggunakan model Order
    $order = Order::find($orderId);
    if (!$order) {
        return response()->json(['success' => false, 'message' => 'Order not found.']);
    }

    // Update payment_status
    $order->payment_status = $paymentStatus;
    $order->save();

    // Hapus produk dari keranjang (cart) berdasarkan user_id atau order_id
    $user = Auth::user();
    cart::where('phone', $user->phone)->delete(); // Sesuaikan dengan kondisi query yang sesuai

    return response()->json(['success' => true]);
}





        
}