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

class HomeApiController extends Controller
{
    public function searchProducts(Request $request)
    {
        $search = $request->get('search'); // query parameter ?search=xxx

        $products = Product::with(['baseprices'])
            ->where('package_type', 'Single')
            ->when($search, function ($query, $search) {
                $query->where('product_name', 'LIKE', "%{$search}%");
            })
            ->orderBy('products.sort_order', 'ASC')
            ->get();

        return response()->json($products);
    }
    public function index(Request $request)
    {
        try {
            $currency = 'INR';

            $products = Product::with(['baseprices'])
                ->where('package_type', 'Single')
                ->orderBy('products.sort_order', 'ASC')
                ->get();

            $productsCombo = Product::with(['baseprices'])
                ->where('package_type', 'Combo')
                ->orderBy('products.sort_order', 'ASC')
                ->get();

            $cartItems = Cart::getContent();
            $videoInstagram = VideoGallary::where('channel', 'Instagram')->get();
            $videoYoutube = VideoGallary::where('channel', 'Youtube')->get();
            $testimonial = Testimonial::all();
            $homeVideoInGallary=HomeVideoGallary::get();
            $whatInFarms=WhatInFarms::get();
            $store=Stores::find(1);
            $categories=Categories::get();
            $aboutusImage=AboutusImage::get();
            $certificates=Certificates::get();

            return response()->json([
                'status' => true,
                'message' => 'Homepage data fetched successfully',
                'currency' => $currency,
                'cartItems' => $cartItems,
                'videoInstagram' => $videoInstagram,
                'videoYoutube' => $videoYoutube,
                'testimonial' => $testimonial,
                'products' => $products,
                'productsCombo' => $productsCombo,
                'homeVideoInGallary'=>$homeVideoInGallary,
                'whatInFarms'=>$whatInFarms,
                'store'=>$store,
                'categories'=>$categories,
                'aboutusImage'=>$aboutusImage,
                'certificates'=>$certificates

            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to load data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
{
    try {
        $product = Product::with([
            'images',
            'sizes',
            'baseprices',
            'prices',
            'reviews',
        ])->findOrFail($id);

        // Related products by category or sub_category (optional logic)
        $relatedProducts = Product::where('id', '!=', $id)
            ->where('category_id', $product->category_id ?? null)
            ->take(6)
            ->with(['baseprices', 'images'])
            ->get();
        $prreviews = Review::with('user')
            ->where('product_id', $id)
            ->where('status', 'Active')
            ->get();
        return response()->json([
            'status' => true,
            'message' => 'Product details loaded successfully',
            'product' => $product,
            'prreviews'=>$prreviews,
            'related_products' => $relatedProducts
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Product not found or error occurred',
            'error' => $e->getMessage()
        ], 404);
    }
}
public function productReviews(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        $reviews = Review::with('user')
            ->where('product_id', $request->product_id)
            ->where('status', 'Active')
            ->get();

        return response()->json([
            'reviews' => $reviews
        ], 200);
    }
}
