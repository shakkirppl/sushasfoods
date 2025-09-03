<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\VideoGallary;
use App\Models\Testimonial;
use App\Models\HomeVideoGallary;
use App\Models\WhatInFarms;
use App\Models\Stores;
use App\Models\Categories;
use App\Models\Certificates;
use App\Models\AboutusImage;
use App\Models\Review;
use Cart;

class SingleViewApiController extends Controller
{
   public function videoGallary(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $data = VideoGallary::where('id', $request->id)
            ->first();

        return response()->json([
            'data' => $data
        ], 200);
    }

       public function testimonial(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $data = Testimonial::where('id', $request->id)
            ->first();

        return response()->json([
            'data' => $data
        ], 200);
    }
           public function whatInFarms(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $data = WhatInFarms::with('images')->where('id', $request->id)
            ->first();

        return response()->json([
            'data' => $data
        ], 200);
    }
    public function whatInFarmsFull(Request $request)
    {
        $data = WhatInFarms::with('images')->limit(8)
    ->where('category', 'main')
    ->orderBy('id', 'DESC')
    ->get();

        return response()->json([
            'data' => $data
        ], 200);
    }
        public function whatInFarms_view_all(Request $request)
    {
        $data = WhatInFarms::with('images')
    ->where('category', 'main')
    ->orderBy('id', 'DESC')
    ->get();
    

        return response()->json([
            'data' => $data
        ], 200);
    }

       public function whatInFarms_most_cultivated(Request $request)
    {
        $data = WhatInFarms::with('images')->limit(8)
    ->where('category', '!=','main')
    ->orderBy('id', 'DESC')
    ->get();

        return response()->json([
            'data' => $data
        ], 200);
    }
        public function whatInFarms_most_cultivated_view_all(Request $request)
    {
        $data = WhatInFarms::with('images')
    ->where('category', 'main')
    ->orderBy('id', 'DESC')
    ->get();
    

        return response()->json([
            'data' => $data
        ], 200);
    }
    
public function whatInFarms_Category(Request $request)
    {
        $data = WhatInFarms::with('images')
    ->where('category', $request->category)
    ->orderBy('id', 'DESC')
    ->get();
    

        return response()->json([
            'data' => $data
        ], 200);
    }
    
    
}
