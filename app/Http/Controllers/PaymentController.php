<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show()
    {
        // Logic to show the payment gateway
        return view('payment.gateway'); // Ensure this view exists
    }
}
