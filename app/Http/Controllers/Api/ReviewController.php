<?php

// app/Http/Controllers/Api/ReviewController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'    => 'required|exists:products,id',
            'user_id'       => 'required|exists:users,id',
            'store_id'      => 'nullable|exists:stores,id',
            'review'        => 'required|string',
            'start_ratings' => 'required|numeric|min:1|max:5',
            'name'          => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $review = Review::create([
            'in_date'       => now()->toDateString(),
            'in_time'       => now()->toTimeString(),
            'product_id'    => $request->product_id,
            'user_id'       => $request->user_id,
            'store_id'      => $request->store_id,
            'review'        => $request->review,
            'start_ratings' => $request->start_ratings,
            'name'          => $request->name,
            'status'        => 'Pending', // default
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Review submitted successfully. Pending approval.',
            'data'    => $review
        ], 201);
    }
}
