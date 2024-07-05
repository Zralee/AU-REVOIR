<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\CreateSnapTokenService;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */


            public function confirmorder(Request $request)
            {
                $user = auth()->user();
                $name = $user->name;
                $phone = $user->phone;
                $address = $user->address;
            
                foreach ($request->productname as $key => $productname) {
                    $product = Product::where('title', $productname)->first();
            
                    // Check if the product exists and has enough stock
                    if ($product && $product->quantity >= $request->quantity[$key]) {
                        $order = new Order;
            
                        $order->user_id = $user->id; // Set the user_id field
                        $order->product_name = $request->productname[$key];
                        $order->price = $request->price[$key];
                        $order->quantity = $request->quantity[$key];
                        $order->name = $name;
                        $order->phone = $phone;
                        $order->address = $address;
                        $order->status = 'not delivered';
            
                        $order->save();
            
                        // Decrease the product stock
                        $product->quantity -= $request->quantity[$key];
                        $product->save();
            
                        // Create Snap Token
                        $snapTokenService = new CreateSnapTokenService($order);
                        $snapToken = $snapTokenService->getSnapToken();
            
                        // Redirect to payment gateway with order and snapToken
                        return redirect()->route('payment.show', [
                            'order_id' => $order->id,
                            'product_name' => $order->product_name,
                            'quantity' => $order->quantity,
                            'price' => $order->price,
                            'snapToken' => $snapToken,
                            'message' => 'Product Ordered Successfully'
                        ]);
                    } else {
                        // If the product does not exist or not enough stock, return with an error message
                        return redirect()->back()->with('error', 'Not enough stock for ' . $productname);
                    }
                }
            
                DB::table('carts')->where('phone', $phone)->delete();
            
                // Redirect to payment gateway
                return redirect()->route('payment.gateway')->with('message', 'Product Ordered Successfully');
            }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
