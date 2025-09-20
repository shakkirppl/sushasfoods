<?php

// app/Http/Controllers/Api/ReviewController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingCharge;
use App\Models\CustomerAddress;
use App\Models\ProductSizes;
use Illuminate\Support\Facades\Validator;
class ShippingController extends Controller
{
    public function calculateShipping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|exists:customer_address,id',
            'items' => 'required|array',
            'items.*.product_size_id' => 'required|exists:product_sizes,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        // Get address & location type
        $address = CustomerAddress::find($request->address_id);
$locationType = (strtolower($address->state) === '1') ? 1 : 0;

        // Calculate total weight
        $totalWeight = 0;
        foreach ($request->items as $item) {
            $productSize = ProductSizes::find($item['product_size_id']);
            $totalWeight += $productSize->weight * $item['qty']; // in grams
        }

        // Find matching slab
        $shipping = ShippingCharge::where('location_type', $locationType)
            ->where('min_weight', '<=', $totalWeight)
            ->where(function ($q) use ($totalWeight) {
                $q->where('max_weight', '>=', $totalWeight)->orWhereNull('max_weight');
            })
            ->orderBy('min_weight')
            ->first();

        if (!$shipping) {
            return response()->json(['status' => 'error', 'message' => 'No shipping charge found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'total_weight' => $totalWeight . ' gm',
            'location' => $locationType,
            'shipping_charge' => $shipping->charge
        ]);
    }
}
