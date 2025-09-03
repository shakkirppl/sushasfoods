<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;
use App\Models\Stores;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\ReturnReason;
use App\Models\ReturnItem;
use App\Models\Countries;
use App\Models\StatesModel;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use DB;
class YourOrdersController extends Controller
{
    //
    public function orders(Request $request)
    {
        try {
            if (Auth::check()) {
    // Authenticated user logic here
        } else {
         return redirect('user-login');
        }
    

            $storeId = 1;
            $currency = 'INR';
            $orders = Order::with([
                'orderdetails.product', // Load product details for each order detail
                'orderdetails.productSize' // Load product size for each order detail
            ])->where('customer_id', Auth::user()->id)->orderBy('id','DESC')->get();
            
            $states= StatesModel::where('country_id', 1)->get();
            $billingStates= StatesModel::where('country_id',1)->get();
             $cartItems = Cart::getContent();
             $customer=Customer::where('user_id',Auth::user()->id)->first();
             $customerAddress=CustomerAddress::with('countrys','states')->where('user_id',Auth::user()->id)->get();
            return view('front-end.your-profile',compact('cartItems','storeId','currency','countries','orders','states','billingStates','customerAddress','customer'));
        } catch (\Exception $e) {
            return $e->getMessage();
          }
    }
    public function return_item($id)
    {
        try {


        $storeId =1;
        $currency = 'INR';

        $returnReason=ReturnReason::get();
        $OrderDetails=OrderDetails::with(
            'products', // Load product details for each order detail
            'sizes' // Load product size for each order detail
        )->find($id);
        $cartItems=[];
        return view('front-end.return-item',compact('cartItems','storeId','currency','countries','OrderDetails','returnReason'));
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }
    
    public function return_item_store(Request $request)
    {
        try {
      
        DB::transaction(function () use ($request) {
        $orderDetails=OrderDetails::find($request->id);
        $order=Order::find($orderDetails->order_id);
        $returnItem=new ReturnItem;
        $returnItem->order_no=$order->id;
        $returnItem->in_date=Carbon::now()->toDatestring();
        $returnItem->in_time=date('H:i:s');
        $returnItem->product_id= $orderDetails->product_id;
        $returnItem->product_size_id=$orderDetails->product_size_id;
        $returnItem->product_prize_id=$orderDetails->product_prize_id;
        $returnItem->country_id=$order->country_id;
        $returnItem->store_id=$orderDetails->store_id;
        $returnItem->order_id=$orderDetails->order_id;
        $returnItem->order_detail_id=$orderDetails->id;
        $returnItem->quantity=$orderDetails->quantity;
        $returnItem->customer_id=$order->customer_id;
        $returnItem->reason_id=$request->reason_id;
        $returnItem->original_price=$orderDetails->price;
        $returnItem->offer_price=$orderDetails->price;
        $returnItem->currency=$orderDetails->currency;
        $returnItem->save();
        
        $orderDetails->status=1;
        $orderDetails->save();
    });
    return redirect('your-orders');
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }
    public function yourprofile(Request $request)
    {
        try {
 

      
        $storeId = 1;
        $currency = 'INR';
        $orders = Order::with([
            'orderdetails.product', // Load product details for each order detail
            'orderdetails.productSize' // Load product size for each order detail
        ])->where('customer_id', Auth::user()->id)->orderBy('id','DESC')->get();
        
        $states= StatesModel::where('country_id',1)->get();
        $billingStates= StatesModel::where('country_id', 1)->get();
         $cartItems = Cart::getContent();
         $customer=Customer::where('user_id',Auth::user()->id)->first();
         $customerAddress=CustomerAddress::with('countrys','states')->where('user_id',Auth::user()->id)->get();
        return view('front-end.your-profile',compact('cartItems','storeId','currency','countries','orders','states','billingStates','customerAddress','customer'));
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }

    
}
