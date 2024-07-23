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
                $data = Product::paginate(8);
                $user = Auth::user();
                $count = Cart::where('user_id', $user->id)->count();
                $orders = Order::where('user_id', $user->id)->where('payment_status', 2)->get();
                $orderCount = Order::where('user_id', $user->id)->where('payment_status', 2)->count(); // Menghitung jumlah order yang sudah dibayar 
                return view('user.home', compact('data', 'count', 'orders', 'orderCount'));
            }
        } else {
            $data = Product::paginate(8);
            return view('user.home', compact('data'))->with('count', 0);
        }
    }

    public function index()
    {
        if (Auth::id()) {
            return redirect('redirect');
        } else {
            $data = Product::paginate(8);
            return view('user.home', compact('data'));
        }
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $user = auth()->user();

        if ($user) {
            $cart = Cart::where('user_id', $user->id)->get();
            $count = Cart::where('user_id', $user->id)->count();
            $orderCount = Order::where('user_id', $user->id)->where('payment_status', 2)->count(); // Menghitung jumlah order yang sudah dibayar 

            if ($search == '') {
                $data = Product::paginate(8);
                return view('user.home', compact('data', 'orderCount', 'search'));
            }

            $data = Product::where('title', 'LIKE', '%' . $search . '%')->paginate(8)->withQueryString();
            return view('user.home', compact('data', 'search'));
        } else {
            if ($search == '') {
                $data = Product::paginate(8);
                return view('user.home', compact('data', 'search'));
            }

            $data = Product::where('title', 'LIKE', '%' . $search . '%')->paginate(8)->withQueryString();
            return view('user.home', compact('data' ,'search'));
        }
    }

    public function addcart(Request $request, $id)
{
    if (Auth::id()) {
        $user = auth()->user();
        $product = Product::find($id);
        $cart = new Cart;

        $cart->user_id = $user->id;
        $cart->name = $user->name;
        $cart->phone = $user->phone;
        $cart->address = $user->address;
        $cart->product_title = $product->title;
        $cart->price = $product->price;
        $cart->quantity = $request->quantity;
        $cart->size = $request->size; // Menyimpan ukuran yang dipilih

        $cart->save();

        return redirect()->back()->with('message', 'Product Added To Cart Successfully');
    } else {
        return redirect('login');
    }
}


    public function showcart()
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->get();
        $count = Cart::where('user_id', $user->id)->count();
        $orders = Order::where('user_id', $user->id)->where('payment_status', 2)->get();
        $orderCount = Order::where('user_id', $user->id)->where('payment_status', 2)->count(); // Menghitung jumlah order yang sudah dibayar 
        return view('user.showcart', compact('count', 'cart', 'orders', 'orderCount'));
    }

    public function showorder()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)->where('payment_status', 2)->get();
        $count = Cart::where('user_id', $user->id)->count(); // Menghitung jumlah item di cart 
        $orderCount = Order::where('user_id', $user->id)->where('payment_status', 2)->count(); // Menghitung jumlah order yang sudah dibayar
        return view('user.showorder', compact('orders', 'count', 'orderCount'));
    }

    public function deletecart($id)
    {
        $data = Cart::find($id);
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
                $user = auth()->user();
                $products = $order->product_name; // Asumsikan product_name menyimpan nama produk dalam bentuk array atau string

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

        if ($product) {
            $selectedSize = $request->size[$key];
            $selectedQuantity = $request->quantity[$key];

            // Cek ketersediaan stok berdasarkan ukuran
            switch ($selectedSize) {
                case 'S':
                    if ($product->quantity_S < $selectedQuantity) {
                        return redirect()->back()->with('error', 'Not enough stock for ' . $productname . ' size S');
                    }
                    break;
                case 'M':
                    if ($product->quantity_M < $selectedQuantity) {
                        return redirect()->back()->with('error', 'Not enough stock for ' . $productname . ' size M');
                    }
                    break;
                case 'L':
                    if ($product->quantity_L < $selectedQuantity) {
                        return redirect()->back()->with('error', 'Not enough stock for ' . $productname . ' size L');
                    }
                    break;
                case 'XL':
                    if ($product->quantity_XL < $selectedQuantity) {
                        return redirect()->back()->with('error', 'Not enough stock for ' . $productname . ' size XL');
                    }
                    break;
                default:
                    return redirect()->back()->with('error', 'Invalid size selected for ' . $productname);
            }

            // Check if the order already exists
            $existingOrder = Order::where([
                ['user_id', '=', $user->id],
                ['product_name', '=', $productname],
                ['size', '=', $selectedSize],
                ['price', '=', $request->price[$key]],
                ['quantity', '=', $selectedQuantity],
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
            $order->user_id = $user->id;
            $order->product_name = $productname;
            $order->size = $selectedSize;
            $order->price = $request->price[$key];
            $order->quantity = $selectedQuantity;
            // $order->quantity_S = $request->quantity_S[$key];
            // $order->quantity_M = $request->quantity_M[$key];
            // $order->quantity_L = $request->quantity_L[$key];
            // $order->quantity_XL = $request->quantity_XL[$key];
            $order->name = $name;
            $order->phone = $phone;
            $order->address = $address;
            $order->recipient_name = $recipientName;
            $order->recipient_email = $recipientEmail;
            $order->recipient_address = $recipientAddress;
            $order->courier = $courier;
            $order->courier_fee = $courierFee;
            $order->total_amount = ($request->price[$key] * $selectedQuantity) + $courierFee;
            $order->status = 'not delivered';
            $order->payment_status = '1'; // Tetap menggunakan '1' untuk payment_status

            $order->save();

            switch ($selectedSize) {
                case 'S':
                    $product->quantity_S -= $selectedQuantity;
                    break;
                case 'M':
                    $product->quantity_M -= $selectedQuantity;
                    break;
                case 'L':
                    $product->quantity_L -= $selectedQuantity;
                    break;
                case 'XL':
                    $product->quantity_XL -= $selectedQuantity;
                    break;
            }

            $product->save();

            $orders[] = $order->id;
        } else {
            return redirect()->back()->with('error', 'Product ' . $productname . ' does not exist');
        }
    }

    if (empty($orders)) {
        return redirect()->back()->with('error', 'No orders were created.');
    }

    $item_details = [];
    $gross_amount = 0;

    foreach ($orders as $orderId) {
        $order = Order::find($orderId);
        $item_details[] = [
            'id'            => $order->id,
            'price'         => $order->price,
            'quantity'      => $order->quantity,
            'name'          => $order->product_name,
            'size'          => $order->size,
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

    $gross_amount += $courierFee;

    $payload = [
        'transaction_details' => [
            'order_id'     => $orders[0],
            'gross_amount' => $gross_amount,
        ],
        'customer_details' => [
            'first_name' => $recipientName,
            'email'      => $recipientEmail,
        ],
        'item_details' => $item_details,
    ];

    $snapToken = $this->createSnapTokenService->getSnapToken($payload);

    // Save order details in session
    session()->put('pending_order', [
        'orders' => $orders,
        'snapToken' => $snapToken,
        'courierFee' => $courierFee,
    ]);

    return redirect()->route('payment.show', [
        'orders' => $orders,
        'snapToken' => $snapToken,
        'courierFee' => $courierFee,
        'message' => 'Product Ordered Successfully',
        'orderId' => $orders[0]
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
