<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Models\Product;

use App\Models\Order;

class AdminController extends Controller
{
    public function product()
    {
        if(Auth::id())
        {
            if(Auth::user()->usertype=='1')
            {
                return view('admin.product');
            }
            else
            {   
                return redirect()->back();
            }
            
        }
        else
        {
            return redirect('login');
        }
    }


    public function dashboardAdmin()
    {
        if (!Auth::check()) {
            return redirect('login');
        }
    
        if (Auth::user()->usertype != '1') {
            return redirect()->back();
        }
    
        $orders = Order::all();
        $products = Product::all();
        return view('admin.dashboardAdmin', ['orders' => $orders,'products' => $products]);
    }

    public function uploadproduct(Request $request)
{
    $data = new Product;

    $image = $request->file('file');
    $imagename = time() . '.' . $image->getClientOriginalExtension();
    $request->file('file')->move('productimage', $imagename);
    $data->image = $imagename;

    $data->title = $request->title;
    $data->price = $request->price;
    $data->description = $request->description;

    // Menyimpan jumlah untuk setiap ukuran
    $data->quantity_S = $request->quantity_S;
    $data->quantity_M = $request->quantity_M;
    $data->quantity_L = $request->quantity_L;
    $data->quantity_XL = $request->quantity_XL;

    $data->save();

    return redirect()->back()->with('message', 'Product Added Successfully');
}


    public function showproduct()
    {
        $data = product::all();
        return view('admin.showproduct',compact('data'));
    }

    public function deleteproduct($id)
    {
        $data = product::find($id);
        $data->delete();
        return redirect()->back()->with('message','Product Deleted Successfully');
    }

    public function updateview($id)
    {
        $data = product::find($id);
        
        return view('admin.updateview',compact('data'));
    }

    public function updateproduct(Request $request, $id)
{
    $data = Product::find($id);
    
    $image = $request->file('file');
    if ($image) {
        $imagename = time() . '.' . $image->getClientOriginalExtension();
        $request->file('file')->move('productimage', $imagename);
        $data->image = $imagename;
    }
    
    $data->title = $request->title;
    $data->price = $request->price;
    $data->description = $request->description;

    // Update jumlah untuk setiap ukuran
    $data->quantity_S = $request->quantity_S;
    $data->quantity_M = $request->quantity_M;
    $data->quantity_L = $request->quantity_L;
    $data->quantity_XL = $request->quantity_XL;

    $data->save();

    return redirect()->back()->with('message', 'Product Updated Successfully');
}

    public function showorder()
    {
        $order= order::all();
        return view('admin.showorder',compact('order'));
    }

    public function updatestatus($id)
    {
        $order=order::find($id);

        $order->status='Delivered';
        $order->save();

        return redirect()->back();
        
    }
}
