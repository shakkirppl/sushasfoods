<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderDetails, CustomerAddress, ShippingCharge, StatesModel, InvoiceNumber, Stores, Customer};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Get all addresses of the logged-in customer
     */
  public function store_order_user(Request $request)
{
    // Validation (basic)
    $request->validate([
        'address' => 'required|exists:customer_address,id',
        'user_id' => 'required|exists:users,id',
        'cart' => 'required|array|min:1',
        'cart.*.product_id' => 'required|integer',
        'cart.*.quantity' => 'required|integer|min:1',
        'cart.*.price' => 'required|numeric|min:0',
        'cart.*.product_size_id' => 'nullable|integer',
        'cart.*.product_prize_id' => 'nullable|integer',
    ]);

    DB::beginTransaction();

    try {
        $selectedAddress = CustomerAddress::findOrFail($request->address);
        $stateId = $selectedAddress->state;

        $shippingCharge = 0;
        $amount = 0;
        $customerId = $request->user_id;

        // Calculate total amount from cart
        foreach ($request->cart as $item) {
            $amount += $item['quantity'] * $item['price'];
        }

        $order = Order::create([
            'order_no' => InvoiceNumber::ReturnInvoice('orders', 1),
            'country_id' => $selectedAddress->country_id,
            'customer_id' => $customerId,
            'first_name' => $selectedAddress->first_name,
            'last_name' => $selectedAddress->last_name,
            'address' => $selectedAddress->address,
            'city' => $selectedAddress->city,
            'state' => $selectedAddress->state,
            'state_code' => optional(StatesModel::find($stateId))->code ?? '',
            'pincode' => $selectedAddress->pincode,
            'phone_number' => $selectedAddress->phone_number,
            'shipping' => $request->input('special_instructions', ''),
            'billing_first_name' => $selectedAddress->first_name,
            'billing_second_name' => $selectedAddress->last_name,
            'billing_address' => $selectedAddress->address,
            'billing_city' => $selectedAddress->city,
            'billing_state' => $selectedAddress->state,
            'billing_post_code' => $selectedAddress->pincode,
            'billing_phone' => $selectedAddress->phone_number,
            'billing_country_id' => $selectedAddress->country_id,
            'store_id' => 1,
            'date' => now(),
            'amount' => $amount,
            'shipping_charge' => $shippingCharge,
            'total_amount' => $amount + $shippingCharge,
            'payment_type' => $request->has('cash_on_delivery') ? 1 : 0,
        ]);

        foreach ($request->cart as $item) {
            OrderDetails::create([
                'order_id' => $order->id,
                'store_id' => 1,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total_amount' => $item['quantity'] * $item['price'],
                'customer_id' => $customerId,
                'currency' => 'INR',
                'product_size_id' => $item['product_size_id'] ?? null,
                'product_prize_id' => $item['product_prize_id'] ?? null,
            ]);
        }

        InvoiceNumber::updateinvoiceNumber('orders', 1);

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Order placed successfully.',
            'order_id' => $order->id,
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => false,
            'message' => 'Order failed.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


}
