<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function showOrders()
    {
        // Ambil semua order dengan status sudah dibayar (payment_status = 2)
        $orders = Order::where('payment_status', 2)->get();
        
        // Hitung jumlah item di cart
        $count = Cart::count(); // Asumsi Anda memiliki model Cart untuk menghitung jumlah item di cart
        
        // Kirim data orders dan count ke view
        return view('user.showorder', compact('orders', 'count'));
    }
}
