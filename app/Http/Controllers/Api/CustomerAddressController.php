<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerAddressController extends Controller
{
    /**
     * Get all addresses of the logged-in customer
     */
    public function index()
    {
        $addresses = CustomerAddress::with(['countrys','states'])
            ->where('user_id', Auth::id())
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Addresses fetched successfully',
            'data' => $addresses
        ]);
    }

    /**
     * Create new address
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'address'      => 'required|string',
            'city'         => 'required|string',
            'state_id'     => 'required|integer',
            'country_id'   => 'required|integer',
            'pincode'      => 'required|string|max:10',
            'phone_number' => 'required|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            $address = new CustomerAddress();
            $address->user_id      = Auth::id();
            $address->first_name   = $request->first_name;
            $address->last_name    = $request->last_name;
            $address->address      = $request->address;
            $address->landmark     = $request->landmark;
            $address->city         = $request->city;
            $address->state        = $request->state_id;
            $address->pincode      = $request->pincode;
            $address->phone_number = $request->phone_number;
            $address->country_id   = $request->country_id;
            $address->store_id     = $request->store_id ?? 1;
            $address->deafult      = 0;
            $address->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Address created successfully',
                'data' => $address
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update existing address
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'address'    => 'required|string',
            'city'       => 'required|string',
            'state_id'   => 'required|integer',
            'country_id' => 'required|integer',
        ]);

        try {
            $address = CustomerAddress::where('user_id', Auth::id())->findOrFail($id);

            $address->first_name   = $request->first_name;
            $address->last_name    = $request->last_name;
            $address->address      = $request->address;
            $address->landmark     = $request->landmark;
            $address->city         = $request->city;
            $address->state        = $request->state_id;
            $address->pincode      = $request->pincode;
            $address->phone_number = $request->phone_number;
            $address->country_id   = $request->country_id;
            $address->save();

            return response()->json([
                'status' => true,
                'message' => 'Address updated successfully',
                'data' => $address
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete address
     */
    public function destroy($id)
    {
        try {
            $address = CustomerAddress::where('user_id', Auth::id())->findOrFail($id);
            $address->delete();

            return response()->json([
                'status' => true,
                'message' => 'Address deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set default address
     */
    public function setDefault($id)
    {
        try {
            $address = CustomerAddress::where('user_id', Auth::id())->findOrFail($id);

            // Set current default
            $address->deafult = 1;
            $address->save();

            // Reset other addresses
            CustomerAddress::where('user_id', Auth::id())
                ->where('id', '!=', $id)
                ->update(['deafult' => 0]);

            return response()->json([
                'status' => true,
                'message' => 'Default address set successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
