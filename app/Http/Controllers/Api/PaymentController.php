<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;   // ✅ Add this line
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    // ✅ Step 1: Create Razorpay Order
    public function createOrder(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $order = $api->order->create([
            'receipt'         => 'rcptid_' . time(),
            'amount'          => $request->amount * 100, // amount in paise
            'currency'        => 'INR',
            'payment_capture' => 1, // auto capture
        ]);

        return response()->json([
            'order_id' => $order['id'],
            'amount'   => $order['amount'],
            'currency' => $order['currency'],
            'key'      => env('RAZORPAY_KEY')
        ]);
    }

    // ✅ Step 2: Verify Payment Signature
    public function verifyPayment(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            $attributes = [
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature
            ];

            $api->utility->verifyPaymentSignature($attributes);

            // ✅ Save payment in DB if needed
            // Payment::create([...]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
