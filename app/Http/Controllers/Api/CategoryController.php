<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Product;

class CategoryController extends Controller
{
    
   public function categoriesWithProducts()
    {
        $categories = Categories::with(['products' => function($query) {
            $query->with('baseprices'); // Include baseprices if needed
                  
        }])->orderBy('sort_order', 'ASC')->get();

        return response()->json($categories);
    }

   public function productsByCategory($categoryId)
{
    $products = Product::with('baseprices')
        ->where('category_id', $categoryId)
        ->orderBy('sort_order', 'ASC')
        ->get();

    return response()->json($products);
}
}
