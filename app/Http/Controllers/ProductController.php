<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Countries;
use App\Models\Categories;
use App\Models\ProductDetail;
use App\Models\ProductAttribute;
use App\Models\ProductPrices;
use App\Models\ProductSizes;
use App\Models\ProductImages;
use App\Models\ProductCountry;
use App\Models\ProductSizeCountry;
use App\Models\ShippingCharge;
use DB;
use App\Helper\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    use File;
    //
    public function getUnits($productId)
{
    try {

    $units = ProductSizes::where('product_id', $productId)
               ->select('id', 'size')
               ->get();

    return response()->json(['units' => $units]);
} catch (\Exception $e) {
    return $e->getMessage();
  }
}
public function index(Request $request)
{ 
    try {

   $products=Product::orderBy('id','ASC')->get();
    return view('products.index', compact('products'));
} catch (\Exception $e) {
    return $e->getMessage();
  }
}
// Function to show the product creation form
public function create(Request $request)
{
   // Fetch Brands and Categories for dropdowns
    $categories=Categories::get();
   return view('products.create',compact('categories'));
}

// Function to store the product
public function store(Request $request)
{
   // return $request->all();
   $validator = Validator::make($request->all(), [
       'product_name' => 'required|string|max:255',
       'single_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,jfif,svg|max:500',
        'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:20480'
   ]);

   if ($validator->fails()) {
       return redirect()->back()->withErrors($validator)->withInput();
   }
   try {
   DB::transaction(function () use ($request) {


       $item_slug = preg_replace('~[^\pL\d]+~u', '-',$request->product_name);  
       $item_slug = iconv('utf-8', 'us-ascii//TRANSLIT', $item_slug);  
       $item_slug = preg_replace('~[^-\w]+~', '', $item_slug);
       $item_slug = trim($item_slug, '-');  
       $item_slug = preg_replace('~-+~', '-', $item_slug);  
       $item_slug = strtolower($item_slug);
       $item_slug=$item_slug;

       if ($request->hasFile('single_image')) {
        $file = $request->file('single_image');
        $path = 'uploads/products';
        $singleImageName = $this->file($file,$path,748,748);
    }
if ($request->hasFile('video')) {
    $videoFile = $request->file('video');

    if ($videoFile->isValid()) {
        $videoName = time() . '_' . $videoFile->getClientOriginalName();
        $videoFile->move(public_path('uploads/products'), $videoName);

        // save $videoName in DB if needed
    }
}
   $product = Product::create([
       'product_name' => $request->product_name,
       'description' => $request->description,
       'description_full' => $request->description_full,
       'product_slug' => $item_slug,
       'package_type' => $request->package_type,
       'category_id' => $request->category_id,
       'image' => $singleImageName,   
       'video' => $videoName ?? null,  
       'video_link' =>$request->video_link ?? null,
   ]);
    $countries=Countries::get();
    foreach($countries as $country)
    {
    $productCountry=new ProductCountry;
    $productCountry->product_id=$product->id;
    $productCountry->countries_id=$country->id;
    $productCountry->is_active=1;
    $productCountry->save();
    }
   
   $productSku = ProductSizes::create([
       'product_id' => $product->id,
       'size' => $request->size,

       'base_unit' => 'Yes',
   ]);

   foreach($countries as $country)
    {
    $productSizeCountry=new ProductSizeCountry;
    $productSizeCountry->product_sizes_id=$productSku->id;
    $productSizeCountry->countries_id=$country->id;
    $productSizeCountry->is_active=1;
    $productSizeCountry->save();
    }

   ProductImages::create([
       'product_id' => $product->id,
       'image_path' => $singleImageName,
   ]);
    // Handle multiple images
$multipleImages = [];
if ($request->hasFile('multiple_images')) {
   foreach ($request->file('multiple_images') as $image) {
       $file =  $image;
       $path = 'uploads/products';
       $imageName = $this->file($file,$path,591,550);
       $multipleImages[] = $imageName;
   }
}

foreach ($multipleImages as $img) {
   ProductImages::create([
       'product_id' => $product->id,
       'image_path' => $img,
   ]);
}
}); 
   // Additional SKU Creation Logic

   return back()->with('success', 'Product Created Successfully');
} catch (\Exception $e) {
   return $e->getMessage();
 }    
}
public function show($id)
{
    try {

    $products=Product::find($id);
    $productSku = ProductSizes::where('product_id', $id)->get();
    $productImage=ProductImages::where('product_id',$id)->get();
    return view('products.view', compact('products','productSku','productImage'));
} catch (\Exception $e) {
    return $e->getMessage();
  }
}

public function addon($id)
{
    try {

    $products=Product::find($id);
    return view('products.addon', compact('products'));
} catch (\Exception $e) {
    return $e->getMessage();
  }
}

public function unitImage($id)
{
    try {

    $products=ProductSizes::find($id);
    return view('products.unit-image-update', compact('products'));
} catch (\Exception $e) {
    return $e->getMessage();
  }
}
public function shippingCharge($id)
{
    try {
    $keralaShippingCharge=ShippingCharge::where('state_id',1)->where('product_size_id',$id)->first();
    $outOfkeralaShippingCharge=ShippingCharge::where('state_id',2)->where('product_size_id',$id)->first();
    $products=ProductSizes::find($id);
    return view('products.shipping-charge-update', compact('products','keralaShippingCharge','outOfkeralaShippingCharge'));
} catch (\Exception $e) {
    return $e->getMessage();
  }
}

public function mainImage($id)
{
    try {

    $products=Product::find($id);
    return view('products.main-image-update', compact('products'));
} catch (\Exception $e) {
    return $e->getMessage();
  }
}

public function products_shipping_charge_india_store(Request $request)
{
    // Validate request
    $validator = Validator::make($request->all(), [
        'product_id' => 'required|exists:product_sizes,id',
        'kerala_shipping' => 'required',
        'out_of_kerala_shipping' => 'required',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Initialize image name variable

    DB::transaction(function () use ($request, &$product) {
        // Check if a file was uploaded
      

        // Retrieve product and update image
        $product = ProductSizes::find($request->product_id);
        if ($product) {
            $keralaShippingCharge=ShippingCharge::where('state_id',1)->where('product_size_id',$request->product_id)->first();
            if($keralaShippingCharge)
            {
                $keralaShippingCharge->shipping_charge=$request->kerala_shipping;
                $keralaShippingCharge->save();
            }
            else{
                $keralaShippingCharge= new ShippingCharge;
                $keralaShippingCharge->store_id=1;
                $keralaShippingCharge->country_id=1;
                $keralaShippingCharge->state_id=1;
                $keralaShippingCharge->product_id=$product->product_id;
                $keralaShippingCharge->product_size_id=$product->id;

                $keralaShippingCharge->shipping_charge=$request->kerala_shipping;
                $keralaShippingCharge->save();
            }

            $outOfkeralaShippingCharge=ShippingCharge::where('state_id',2)->where('product_size_id',$request->product_id)->first();
            if($outOfkeralaShippingCharge)
            {
                $outOfkeralaShippingCharge->shipping_charge=$request->out_of_kerala_shipping;
                $outOfkeralaShippingCharge->save();
            }
            else{
                $outOfkeralaShippingCharge= new ShippingCharge;
                $outOfkeralaShippingCharge->store_id=1;
                $outOfkeralaShippingCharge->country_id=1;
                $outOfkeralaShippingCharge->state_id=2;
                $outOfkeralaShippingCharge->product_id=$product->product_id;
                $outOfkeralaShippingCharge->product_size_id=$product->id;
                $outOfkeralaShippingCharge->shipping_charge=$request->out_of_kerala_shipping;
                $outOfkeralaShippingCharge->save();
            }
        }
    });

    // Redirect back with success message
    return redirect()->route('products.show', $product->product_id)->with('success', 'Product image updated successfully');
}
public function products_unit_image_store(Request $request)
{
    // Validate request
    $validator = Validator::make($request->all(), [
        'product_id' => 'required|exists:product_sizes,id',
       'single_image' => 'required|image|mimes:webp,jpeg,png,jpg,webp,svg|max:500',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Initialize image name variable
    $singleImageName = null;

    DB::transaction(function () use ($request, &$product) {
        // Check if a file was uploaded
        if ($request->hasFile('single_image')) {
            $file = $request->file('single_image');
            $path = 'uploads/products'; // Define the upload path
            $singleImageName = $this->file($file, $path, 150, 150); // Assuming file() method processes the image
        }

        // Retrieve product and update image
        $product = ProductSizes::find($request->product_id);
        if ($product) {
            $product->image = $singleImageName; // Update product image
            $product->save(); // Save changes
        }
    });

    // Redirect back with success message
    return redirect()->route('products.show', $product->product_id)->with('success', 'Product image updated successfully');
}
public function detailImage($id)
{
    try {

    $products=ProductImages::find($id);
    return view('products.detail-image-update', compact('products'));
} catch (\Exception $e) {
    return $e->getMessage();
  }
}

public function products_main_image_store(Request $request)
{
    // Validate request
    $validator = Validator::make($request->all(), [
        'product_id' => 'required|exists:products,id',
       'single_image' => 'required|image|mimes:webp,jpeg,png,jpg,svg|max:500',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Initialize image name variable
    $singleImageName = null;

    DB::transaction(function () use ($request, &$singleImageName) {
        // Check if a file was uploaded
        if ($request->hasFile('single_image')) {
            $file = $request->file('single_image');
            $path = 'uploads/products'; // Define the upload path
            $singleImageName = $this->file($file, $path, 748, 748); // Assuming file() method processes the image
        }

        // Retrieve product and update image
        $product = Product::find($request->product_id);
        if ($product) {
            $product->image = $singleImageName; // Update product image
            $product->save(); // Save changes
        }
    });

    // Redirect back with success message
    return redirect()->route('products.show', $request->product_id)->with('success', 'Product image updated successfully');
}
public function products_detail_image_store(Request $request)
{
    // Validate request
    $validator = Validator::make($request->all(), [
        'product_image_id' => 'required|exists:product_images,id',
        'single_image' => 'required|image|mimes:webp,jpeg,png,jpg,svg|max:500',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Initialize image name variable
    $singleImageName = null;

    DB::transaction(function () use ($request, &$singleImageName,&$product) {
        // Check if a file was uploaded
        if ($request->hasFile('single_image')) {
            $file = $request->file('single_image');
            $path = 'uploads/products'; // Define the upload path
            $singleImageName = $this->file($file, $path, 591, 550); // Assuming file() method processes the image
        }

        // Retrieve product and update image
        $product = ProductImages::find($request->product_image_id);
        if ($product) {
            $product->image = $singleImageName; // Update product image
            $product->save(); // Save changes
        }
    });

    // Redirect back with success message
    return redirect()->route('products.show', $product->product_id)->with('success', 'Product image updated successfully');
}

public function storeSku(Request $request)
{
    // return $request->all();
    $validator = Validator::make($request->all(), [
        'product_id' => 'required|exists:products,id',
        'size' => 'required|string',

    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }
    DB::transaction(function () use ($request) {
        
        $productSku = ProductSizes::create([
            'product_id' => $request->product_id,
            'size' => $request->size,
            'size_ar' => '',
            'base_unit' => 'No',
        ]);
        $countries=Countries::get();
        foreach($countries as $country)
        {
        $productSizeCountry=new ProductSizeCountry;
        $productSizeCountry->product_sizes_id=$productSku->id;
        $productSizeCountry->countries_id=$country->id;
        $productSizeCountry->is_active=1;
        $productSizeCountry->save();
        }
}); 
    // Handle Images
    return back()->with('success', 'Product Created Successfully');
}
public function destroySku($id) 
{
    try {
        // Find the SKU record by its ID
        $tempProductSku = ProductSizes::findOrFail($id);

        // Wrap in a transaction to ensure atomicity
        DB::transaction(function () use ($tempProductSku) {
            ProductSizeCountry::where('product_sizes_id', $tempProductSku->id)->delete();
            $tempProductSku->delete();
        }); 

        return back()->with('success', 'SKU deleted successfully');
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to delete SKU: ' . $e->getMessage());
    }
}
public function edit($id)
{
    try {
  $categories=Categories::get();
    $products=Product::find($id);
    return view('products.edit', compact('products','categories'));
} catch (\Exception $e) {
    return $e->getMessage();
  }
}
public function updateProduct(Request $request)
{
    // return $request->all();
    $validator = Validator::make($request->all(), [
        'product_name' => 'required|string|max:255',
       'product_id' => 'required|exists:products,id',
     
    ]);
  $videoName = null;
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }
    try {
        if ($request->hasFile('video')) {
    $videoFile = $request->file('video');

    if ($videoFile->isValid()) {
        $videoName = time() . '_' . $videoFile->getClientOriginalName();
        $videoFile->move(public_path('uploads/products'), $videoName);

        // save $videoName in DB if needed
    }
}
    DB::transaction(function () use ($request,$videoName ) {

        $product = Product::find($request->product_id);
       $item_slug = preg_replace('~[^\pL\d]+~u', '-', $request->product_name);
$item_slug = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $item_slug) ?: $request->product_name;
$item_slug = preg_replace('~[^-\w]+~', '', $item_slug);
$item_slug = trim($item_slug, '-');
$item_slug = preg_replace('~-+~', '-', $item_slug);
$item_slug = strtolower($item_slug);

        $product->product_name = $request->product_name;
        $product->product_slug = $item_slug;
        $product->description = $request->description;
        $product->description_full=$request->description_full;
        $product->package_type = $request->package_type;
        $product->category_id = $request->category_id;
       if ($videoName) {
                // optionally delete old video file here if you want
                $product->video = $videoName;
            }
        $product->video_link=$request->video_link ?? null;
        $product->save();
        
        $multipleImages = [];
if ($request->hasFile('multiple_images')) {
   foreach ($request->file('multiple_images') as $image) {
       $file =  $image;
       $path = 'uploads/products';
       $imageName = $this->file($file,$path,591,550);
       $multipleImages[] = $imageName;
   }
}

foreach ($multipleImages as $img) {
   ProductImages::create([
       'product_id' => $product->id,
       'image_path' => $img,
   ]);
}
}); 
    // Additional SKU Creation Logic

    return back()->with('success', 'Product Updated Successfully');
} catch (\Exception $e) {
    return $e->getMessage();
  }    
}
}
