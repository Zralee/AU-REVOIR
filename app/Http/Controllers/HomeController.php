<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;

class HomeController extends Controller
{
    public function redirect()
{
    if (Auth::check())
     {
        $usertype = Auth::user()->usertype;

        if ($usertype == '1') 
        {
            return view('admin.home');
        } 
        else 
        {
            $data = product::paginate(4);
            $user = Auth::user();
            $count = cart::where('phone', $user->phone)->count();
            return view('user.home', compact('data', 'count'));
        }
    } 
    else 
    {
        // Logika untuk pengguna yang belum login
        $data = product::paginate(4);
        return view('user.home', compact('data'))->with('count', 0);
    }
}

    public function index()
    {
        if(Auth::id())
        {
            return redirect('redirect');
        }
        else
        {
            $data = product::paginate(4);
            return view('user.home',compact('data'));
            
        }
        
    }

    public function search(Request $request)
{
    $search = $request->search;

    $user = auth()->user();
   

    if($user){
        $cart = Cart::where('phone', $user->phone)->get();
        $count = Cart::where('phone', $user->phone)->count();
    if ($search == '') {
        $data = Product::paginate(4);
        return view('user.home', compact('data', 'count', 'search'));
    }

    $data = Product::where('title', 'LIKE', '%' . $search . '%')->paginate(4)->withQueryString();

    return view('user.home', compact('data', 'count', 'search'));
}
else{

    if ($search == '') {
        $data = Product::paginate(3);
        return view('user.home', compact('data', 'search'));
    }

    $data = Product::where('title', 'LIKE', '%' . $search . '%')->paginate(4)->withQueryString();

    return view('user.home', compact('data','search'));
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
        $user =auth()->user();
        $cart =cart::where('phone',$user->phone)->get();
        $count =cart::where('phone',$user->phone)->count();
        return view('user.showcart',compact('count','cart'));
    }

    public function deletecart($id)
    {
        $data=cart::find($id);
        $data->delete();
        return redirect()->back()->with('message','Product Removed Successfully');

        
    }

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
            } else {
                // If the product does not exist or not enough stock, return with an error message
                return redirect()->back()->with('error', 'Not enough stock for ' . $productname);
            }
        }
    
        DB::table('carts')->where('phone', $phone)->delete();
    
        // Redirect to payment gateway
        return redirect()->route('payment.gateway')->with('message', 'Product Ordered Successfully');
    }
    

    public function aboutus()
    {
        $user =auth()->user();

        if($user){
        $cart =cart::where('phone',$user->phone)->get();
        $count =cart::where('phone',$user->phone)->count();
        return view('user.aboutus',compact('count','cart'));
        }
        else{
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
        $user =auth()->user();

        if($user){
        $cart =cart::where('phone',$user->phone)->get();
        $count =cart::where('phone',$user->phone)->count();
        return view('user.contactus',compact('count','cart'));
        }
        else{
            return view('user.contactus');

        }
    }
    

}
