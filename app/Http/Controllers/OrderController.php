<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

use App\Models\ReturnItem;
use App\Models\shipmentdetailsinternational;
use App\Models\ShippmentDetailsUae;
use App\Models\DeliveryStatus;
use App\Models\OrderDetails;
use App\Models\ProductSizes;
use App\Models\Product;
use App\Models\ShippmentDetailsInternational;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    //
    public function show_pending_orders(Request $request)
    {
        try {

       
        $orders = Order::with('orderdetails', 'store')->where('delivery_status', 'Pending')
        ->orderBy('id', 'desc') 
        ->get();
        
        return view('orders.index',compact('orders'));
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }

    public function show_accepted_orders(Request $request)
    {
        try {

      
        $orders = Order::with('orderdetails', 'store')
        ->where('delivery_status', 'Accepted')
     
        ->orderBy('id', 'desc') 
        ->get();
        return view('orders.index',compact('orders'));
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }
    public function show_packed_orders(Request $request)
    {
        try {

       
        $orders = Order::with('orderdetails', 'store')
        ->where('delivery_status', 'Packed')
        ->where('mode','website')
        ->orderBy('id', 'desc') 
        ->get();
        return view('orders.index',compact('orders'));
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }

    public function show_delivered_orders(Request $request)
    {
        try {

      
        $orders = Order::with('orderdetails', 'store')
        ->where('delivery_status', 'Delivered')
        ->where('mode','website')
        ->orderBy('id', 'desc') 
        ->get();
        return view('orders.index',compact('orders'));
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }
    public function canceled_order(Request $request)
    {
        try {

        $orders = Order::with('orderdetails', 'store')
        ->where('delivery_status', 'Cancel')
        ->orderBy('id', 'desc') 
        ->get();
        return view('orders.index',compact('orders'));
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }
  

    public function order_view(Request $request , $id)
    {
        try {
       
         $orders = Order::with('store','country','deliverystate','billingstate','billingcountry','detail')->find( $id);
        $d_status = DeliveryStatus::all();
       
        return view('orders.order-view',compact('orders'));
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }


    public function updateStatus(Request $request)
    {
       
        $order=Order::find($request->order_id);
        $order->update(['delivery_status' => $request->d_status]);
    
     return redirect('accepted-orders');
    }


    public function updateStatus1(Request $request, $id)
    {
    
        $order = Order::findOrFail($id);
    
        // Validate the status value
        $request->validate([
            'd_status' => 'required|in:Pending,Accepted,Packed,Delivered',
        ]);
        try {
        $order->delivery_status = $request->d_status;
        if ($request->d_status === 'Packed' && $request->has('delivery_date')) {
            $order->delivery_date = $request->delivery_date;
        }
        $order->save();

        if ($request->has('delivery_date')) {
       
      
      }

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }


    public function updateDeliveryDate(Request $request, Order $order)
    {
        $request->validate([
            'delivery_date' => 'required|date',
        ]);
    
        $order->update(['delivery_date' => $request->delivery_date]);
    
        return response()->json(['message' => 'Delivery date updated successfully!']);
    }


    public function orders_tracking(Request $request)
    {
        $query = Order::query();
    
        // Apply filters if present in the request
        if ($request->filled('delivery_status')) {
            $query->where('delivery_status', $request->delivery_status);
        }
        if ($request->filled('order_no')) {
            $query->where('order_no', 'LIKE', '%' . $request->order_no . '%');
        }
        if ($request->filled('first_name')) {
            $query->where('first_name', 'LIKE', '%' . $request->first_name . '%');
        }
        if ($request->filled('phone_number')) {
            $query->where('phone_number', 'LIKE', '%' . $request->phone_number . '%');
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
          $query->whereBetween('date', [$request->from_date, $request->to_date]);
      }
    
        $orders = $query->with('store')->paginate(10);
    
        return view('orders.order-tracking', compact('orders'));
    }
    public function website_orders_tracking(Request $request)
    {
     
    $query = Order::where('store_id', 1); // No need for `query()`
    
    // Apply filters if present in the request
    if ($request->filled('delivery_status')) {
        $query->where('delivery_status', $request->delivery_status);
    }
    if ($request->filled('order_no')) {
        $query->where('order_no', 'LIKE', '%' . $request->order_no . '%');
    }
    if ($request->filled('first_name')) {
        $query->where('first_name', 'LIKE', '%' . $request->first_name . '%');
    }
    if ($request->filled('phone_number')) {
        $query->where('phone_number', 'LIKE', '%' . $request->phone_number . '%');
    }
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('date', [$request->from_date, $request->to_date]);
    }
    $products = Product::all();
    // Fetch orders with store relation and paginate
    $orders = $query->with('store')->paginate(10);

    return view('orders.website-order-tracking', compact('orders','products'));
    }
    
    public function return_items_pending(Request $request)
    {
        try {

      
        $orders = ReturnItem::with('product', 'productSize','order','customer','reason')
        ->where('status', 'Pending')
        ->orderBy('id', 'desc') 
        ->get();
        return view('orders.return-items',compact('orders'));
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }

    public function return_item_collected(Request $request, $id)
    {
        $return = ReturnItem::findOrFail($id);
    
     
        try {
        $return->status = 'Collected';
        $return->save();
    
        return back();
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }

 
    public function printInvoice($id)
    {
        $order = Order::with('orderdetails', 'store', 'billingstate', 'billingcountry')->findOrFail($id);
        $currency = $order->store->currency;
    
        return view('orders.print-invoice', compact('order', 'currency'));
    }
    public function printSelected(Request $request)
    {
        // Get selected order IDs
        $orderIds = $request->input('order_ids');

        if (!$orderIds) {
            return back()->with('error', 'No orders selected!');
        }

        // Fetch orders
        $orders = Order::with('orderdetails', 'store', 'billingstate', 'billingcountry')->whereIn('id', $orderIds)->get();
        $currency='INR';
        // Process invoices (e.g., generate PDFs or return a view)
        return view('orders.multi-print', compact('orders','currency'));
    }
}
