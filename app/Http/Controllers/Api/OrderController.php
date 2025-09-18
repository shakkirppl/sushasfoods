<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderDetails, CustomerAddress, ShippingCharge, StatesModel, InvoiceNumber, Stores, Customer};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Get all addresses of the logged-in customer
     */
public function getOrders($user_id)
{
    $orders = Order::with('orderdetails')
                ->where('customer_id', $user_id)
                ->orderByDesc('created_at')
                ->get();

    return response()->json($orders);
}
public function getOrderItems($order_id)
{
    $items = OrderDetails::with('product')->where('order_id', $order_id)->get();
    return response()->json($items);
}
public function cancelOrder($order_id)
{
    $order = Order::findOrFail($order_id);
    // if ($order->status !== 'Placed') {
    //     return response()->json(['error' => 'Only placed orders can be canceled.'], 403);
    // }

    $order->update(['status' => 'Cancelled']);

    OrderDetails::where('order_id', $order_id)->update(['status' => 'Cancelled']);

    return response()->json(['message' => 'Order cancelled successfully.']);
}
public function returnItem(Request $request)
{
    $request->validate([
        'order_item_id' => 'required',
        'reason' => 'nullable|string|max:255',
    ]);

    $item = OrderDetails::findOrFail($request->order_item_id);
    if ($item->status !== 'Delivered') {
        return response()->json(['error' => 'Only delivered items can be returned.'], 403);
    }

    $item->update(['status' => 'Returned', 'return_reason' => $request->reason]);

    return response()->json(['message' => 'Item marked as returned.']);
}
}
