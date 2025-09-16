<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller; // ✅ Fixed line
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
class CustomerApiController extends Controller
{
    //
   public function customer_store(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            'name' => 'required|string|min:24|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'mobile' => [
                'required',
                'string',
                'min:4',
                'max:15',
                'unique:customers,phone_number',
                'regex:/^[6-9]\d{9}$/',
            ],
            'password' => 'required|string|min:4',
        ],
        [ // Custom messages
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 2 characters.',
            
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            'email.unique' => 'This email already exists.',

            'mobile.required' => 'Mobile number is required.',
            'mobile.unique' => 'This mobile number already exists.',
            'mobile.regex' => 'Mobile number must be 10 digits starting with 6-9.',
            'mobile.min' => 'Mobile number must be at least 4 digits.',
            
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 4 characters.',
        ]
    );

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        DB::transaction(function () use ($request, &$customer, &$token) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 5,
                'created_by'=>0,
                'store_id' => 1,
            ]);

            $customer = new Customer();
            $customer->first_name = $request->name;
            $customer->phone_number = $request->mobile;
            $customer->user_id = $user->id;
            $customer->save();

            $token = $user->createToken('api_token')->plainTextToken;
        });

        return response()->json([
            'status' => 'success',
            'data' => $customer,
            'token' => $token,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create customer: ' . $e->getMessage(),
        ], 500);
    }
}
  public function user_update(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|integer|exists:users,id',
        'name' => 'required|string|max:255',
        'mobile' => ['required', 'string', 'max:255', 'regex:/^[6-9]\d{9}$/'],

    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $customer = Customer::where('user_id', $request->user_id)->first();

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found',
            ], 404);
        }

        DB::transaction(function () use ($request, $customer) {
            $customer->update([
                'first_name' => $request->name,
                'phone_number' => $request->mobile,
                'gender' => $request->gender
            ]);

            User::where('id', $request->user_id)->update([
                'name' => $request->name
            ]);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'User details updated successfully',
            'data' => $customer,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to update user: ' . $e->getMessage(),
        ], 500);
    }
}
   public function customer_detail(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|integer|exists:users,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $customer = Customer::where('user_id', $request->user_id)->first();

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User details retrieved successfully',
            'data' => $customer,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to retrieve customer: ' . $e->getMessage(),
        ], 500);
    }
}
    
}
