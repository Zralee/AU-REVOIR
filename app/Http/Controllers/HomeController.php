<?php

namespace App\Http\Controllers;

use App\Services\Midtrans\CreateSnapTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;

class HomeController extends Controller
{
    private CreateSnapTokenService $createSnapTokenService;
    private string $controllerName = '[CategoryController] ';

    public function __construct(CreateSnapTokenService $createSnapTokenService)
    {
        $this->createSnapTokenService = $createSnapTokenService;
    }
    public function redirect()
    {
        if (Auth::check()) {
            $usertype = Auth::user()->usertype;

            if ($usertype == '1') {
                return view('admin.home');
            } else {
                $data = product::paginate(8);
                $user = Auth::user();
                $count = cart::where('phone', $user->phone)->count();
                $orders = Order::where('payment_status', 2)->get();
                $orderCount = Order::where('payment_status', 2)->count(); // Menghitung jumlah order yang sudah dibayar 
                return view('user.home', compact('data', 'count', 'orders','orderCount'));
            }
        } else {
            // Logika untuk pengguna yang belum login
            $data = product::paginate(8);
            return view('user.home', compact('data'))->with('count', 0);
        }
    }

    public function index()
    {
        if (Auth::id()) {
            return redirect('redirect');
        } else {
            $data = product::paginate(8);
            return view('user.home', compact('data'));
        }
    }

    public function search(Request $request)
    {
        $search = $request->search;

        $user = auth()->user();


        if ($user) {
            $cart = Cart::where('phone', $user->phone)->get();
            $count = Cart::where('phone', $user->phone)->count();
            if ($search == '') {
                $data = Product::paginate(8);
                return view('user.home', compact('data', 'count', 'search'));
            }

            $data = Product::where('title', 'LIKE', '%' . $search . '%')->paginate(4)->withQueryString();

            return view('user.home', compact('data', 'count', 'search'));
        } else {

            if ($search == '') {
                $data = Product::paginate(3);
                return view('user.home', compact('data', 'search'));
            }

            $data = Product::where('title', 'LIKE', '%' . $search . '%')->paginate(4)->withQueryString();

            return view('user.home', compact('data', 'search'));
        }
    }

    public function addcart(Request $request, $id)
    {
        if (Auth::id()) {
            $user = auth()->user();
            $product = product::find($id);
            $cart = new cart;

            $cart->user_id = $user->id; // Set the user_id field
            $cart->name = $user->name;
            $cart->phone = $user->phone;
            $cart->address = $user->address;
            $cart->product_title = $product->title;
            $cart->price = $product->price;
            $cart->quantity = $request->quantity;

            $cart->save();

            return redirect()->back()->with('message', 'Product Added To Cart Successfully');
        } else {
            return redirect('login');
        }
    }

    public function showcart()
    {
        $user = auth()->user();
        $cart = cart::where('phone', $user->phone)->get();
        $count = cart::where('phone', $user->phone)->count();
        $orders = Order::where('payment_status', 2)->get();
        $orderCount = Order::where('payment_status', 2)->count(); // Menghitung jumlah order yang sudah dibayar 
        return view('user.showcart', compact('count', 'cart', 'orders','orderCount'));
    }
    public function showorder()
    {
        $orders = Order::where('payment_status', 2)->get();
        $count = Cart::count(); // Menghitung jumlah item di cart (sesuaikan dengan kebutuhan Anda)
        $orderCount = Order::where('payment_status', 2)->count(); // Menghitung jumlah order yang sudah dibayar
        return view('user.showorder', compact('orders', 'count', 'orderCount'));
    }

    public function deletecart($id)
    {
        $data = cart::find($id);
        $data->delete();
        return redirect()->back()->with('message', 'Product Removed Successfully');
    }




    public function updatePaymentStatus(Request $request)
{
    $orderId = $request->input('order_id');
    $paymentStatus = $request->input('payment_status');

    $order = Order::find($orderId);
    if ($order) {
        $order->payment_status = $paymentStatus;
        $order->save();

        if ($paymentStatus == 2) {
            // Hapus produk dari keranjang
            $user = auth()->user();
            $products = $order->product_name; // Asumsikan product_name menyimpan nama produk dalam bentuk array atau string

            // Menghapus produk dari keranjang berdasarkan nama produk
            foreach ($products as $product) {
                Cart::where('user_id', $user->id)
                    ->where('product_title', $product)
                    ->delete();
            }
        }

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false]);
}
//     public function clearCartAfterPayment()
// {
//     // Menghapus semua item dari keranjang untuk user yang sedang login
//     Cart::where('user_id', auth()->id())->delete();

//     return response()->json(['success' => true]);
// }


public function confirmOrder(Request $request)
{
    $user = auth()->user();
    $name = $user->name;
    $phone = $user->phone;
    $address = $user->address;

    $orders = [];

    // Informasi penerima dari form
    $recipientName = $request->input('recipient_name');
    $recipientEmail = $request->input('recipient_email');
    $recipientAddress = $request->input('recipient_address');
    $courier = $request->input('courier');
    $courierFee = $courier === 'JNE' ? 25000 : ($courier === 'JNT' ? 20000 : 0);

    // Proses setiap produk dalam keranjang
    foreach ($request->productname as $key => $productname) {
        $product = Product::where('title', $productname)->first();

        // Check if the product exists and has enough stock
        if ($product && $product->quantity >= $request->quantity[$key]) {
            // Check if the order already exists
            $existingOrder = Order::where([
                ['user_id', '=', $user->id],
                ['product_name', '=', $request->productname[$key]],
                ['price', '=', $request->price[$key]],
                ['quantity', '=', $request->quantity[$key]],
                ['recipient_name', '=', $recipientName],
                ['recipient_email', '=', $recipientEmail],
                ['recipient_address', '=', $recipientAddress],
                ['courier', '=', $courier],
                ['courier_fee', '=', $courierFee],
                ['status', '=', 'not delivered']
            ])->first();

            if ($existingOrder) {
                // Skip this iteration if order already exists
                continue;
            }

            $order = new Order;

            $order->user_id = $user->id; // Set the user_id field
            $order->product_name = $request->productname[$key];
            $order->price = $request->price[$key];
            $order->quantity = $request->quantity[$key];
            $order->name = $name;
            $order->phone = $phone;
            $order->address = $address;
            $order->recipient_name = $recipientName;
            $order->recipient_email = $recipientEmail;
            $order->recipient_address = $recipientAddress;
            $order->courier = $courier;
            $order->courier_fee = $courierFee;
            $order->total_amount = ($request->price[$key] * $request->quantity[$key]) + $courierFee; // Menghitung total_amount
            $order->status = 'not delivered';
            $order->payment_status = '1'; // Menggunakan string untuk inisialisasi payment_status

            $order->save();

            // Decrease the product stock
            $product->quantity -= $request->quantity[$key];
            $product->save();

            $orders[] = $order->id;
        } else {
            return redirect()->back()->with('error', 'Not enough stock for ' . $productname);
        }
    }

    // Check if orders were created
    if (empty($orders)) {
        return redirect()->back()->with('error', 'No orders were created.');
    }

    // Prepare $payload based on $orders
    $item_details = [];
    $gross_amount = 0;

    foreach ($orders as $orderId) {
        $order = Order::find($orderId);
        $item_details[] = [
            'id'            => $order->id,
            'price'         => $order->price,
            'quantity'      => $order->quantity,
            'name'          => $order->product_name,
            'total'         => $order->total_amount,
            'merchant_name' => config('app.name'),
        ];

        $gross_amount += $order->total_amount;
    }

    $item_details[] = [
        'id'            => 'courier_fee',
        'price'         => $courierFee,
        'quantity'      => 1,
        'name'          => 'Courier Fee',
        'total'         => $courierFee,
        'merchant_name' => config('app.name'),
    ];

    // Add courier fee to total gross amount
    $gross_amount += $courierFee;

    // Construct the $payload array
    $payload = [
        'transaction_details' => [
            'order_id'     => $orders[0], // Assuming first order's ID as order_id
            'gross_amount' => $gross_amount, // Total gross amount including courier fee
        ],
        'customer_details' => [
            'first_name' => $recipientName,
            'email'      => $recipientEmail,
        ],
        'item_details' => $item_details, // Array of item details for each order
    ];

    // Create Snap Token
    $snapToken = $this->createSnapTokenService->getSnapToken($payload);

    // Redirect to payment gateway
    return redirect()->route('payment.show', [
        'orders' => $orders,  // Passing all orders
        'snapToken' => $snapToken,
        'courierFee' => $courierFee,
        'message' => 'Product Ordered Successfully',
        'orderId' => $orders[0] // Assuming first order's ID as order_id
    ]);
}


// Tambahkan metode ini untuk mengupdate payment_status setelah pembayaran berhasil

//ini masih belom bisa coyyyyyy

// public function updatePaymentStatus($orderId)
// {
//     $order = Order::find($orderId);
//     if ($order) {
//         $order->payment_status = '2'; // Menggunakan string untuk payment_status
//         $order->save();
//     }
// }



public function paymentSuccess(Request $request)
{
    $orderId = $request->input('order_id');

    $order = Order::find($orderId);
    if ($order) {
        $order->payment_status = '2'; // Menggunakan string untuk payment_status
        $order->save();
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false]);
}



    public function aboutus()
    {
        $user = auth()->user();

        if ($user) {
            $cart = cart::where('phone', $user->phone)->get();
            $count = cart::where('phone', $user->phone)->count();
            $orders = Order::where('payment_status', 2)->get();
            $orderCount = Order::where('payment_status', 2)->count(); // Menghitung jumlah order yang sudah dibayar
            return view('user.aboutus', compact('count', 'cart', 'orders','orderCount'));
        } else {
            return view('user.aboutus');
        }
    }

    public function ourproducts()
    {
        // $usertype=Auth::user()->usertype;

        // if($usertype=='1')
        // {
        //     return view('admin.home');
        // }
        return view('user.ourproducts');
        // else
        // {
        //     $data = product::paginate(9);
        //     $user =auth()->user();
        //     $count =cart::where('phone',$user->phone)->count();
        //     return view('user.ourproducts',compact('data','count'));
        // }
    }

    public function contactus()
    {
        $user = auth()->user();

        if ($user) {
            $cart = cart::where('phone', $user->phone)->get();
            $count = cart::where('phone', $user->phone)->count();
            $orders = Order::where('payment_status', 2)->get();
            $orderCount = Order::where('payment_status', 2)->count(); // Menghitung jumlah order yang sudah dibayar 
            return view('user.contactus', compact('count', 'cart', 'orders','orderCount'));
        } else {
            return view('user.contactus');
        }
    }

        //     public function showOrderDetails()
        // {
        //     $orders = Order::all(); // Ambil semua order dari database
        //     $ordersTotalAmount = $orders->sum(function($order) {
        //         return $order->price * $order->quantity;
        //     });

        //     return view('order-details', [
        //         'orders' => $orders,
        //         'ordersTotalAmount' => $ordersTotalAmount,
        //     ]);
        // }
}
