<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductPrices;
use App\Models\ProductSizes;
use App\Models\Stores;
use App\Models\Countries;
use App\Models\VideoGallary;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\StatesModel;
use App\Models\Testimonial;
use App\Models\Visitors;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Http;
class HomeController extends Controller
{
    //
    public function lang_change($id)
    {
        try {

 
        if ($id == 'ar') {
            App::setLocale('ar');
            Session::put('locale', 'ar'); // Store language in session
        } else {
            App::setLocale('en');
            Session::put('locale', 'en'); // Store language in session
        }
      
        return redirect()->back();
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }
    public function setCountry(Request $request)
    {
        // Validate the incoming country ID
    // return $request->all();
        $validated = $request->validate([
            'country' => 'required|exists:countries,code',
        ]);
 
        try {
     
        // return $request->all();
        // Store the selected country in the session
        $request->session()->put('activecountry', $request->country);

        Cart::clear();
        // Redirect to the homepage or any other desired route
        return redirect('/');
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }
    public function index(Request $request)
{ 
    try {

          $currency = 'INR';

         $products = Product::with(['baseprices' => function ($query)  {
        }])->where('package_type','Single')
        ->orderby('products.sort_order','ASC')
        ->get();

        $productsCombo = Product::with(['baseprices' => function ($query)  {
         
        }])->where('package_type','Combo')
        ->orderby('products.sort_order','ASC')
        ->get();
        $cartItems = Cart::getContent();
        $videoInstagram=VideoGallary::where('channel','Instagram')->get();
        $testimonial=Testimonial::get();
        $videoYoutube=VideoGallary::where('channel','Youtube')->get();


      return view('front-end.index',compact('currency','cartItems','videoInstagram','videoYoutube','testimonial','products','productsCombo'));
     
} catch (\Exception $e) {
    return $e->getMessage();
  }     
}
public function combo(Request $request)
{ 
    try {

    

         
         
          $countryCod = $data['country_code2'] ?? 'IN'; // Example: 'IN'
          Session::put('activecountry', 'IN');
          $language = app()->getLocale() == 'ar' ? 'ar' : 'en';
          $store = Stores::where('countryCode', $countryCod)->first();
          $storeId = $store->id ?? 1;
          $currency = $store->currency ?? 'INR';

       
        // return  $products = Product::WithLanguageName()->with(['baseprices'])->where('package_type','Single')->get();

        

        $productsCombo = Product::WithLanguageName()->with(['baseprices' => function ($query) use ($storeId) {
            $query->where('product_prices.store_id', $storeId);
        }])->where('package_type','Combo')
        ->whereHas('countries', function ($query) use ($storeId) {
            $query->where('countries_id', $storeId)->where('is_active', 1);
        })
        ->orderby('products.sort_order','ASC')
        ->get();
      
        $countries=Countries::get();
        $cartItems = Cart::getContent();
      return view('front-end.combo-index',compact('storeId','currency','countries','language','cartItems','productsCombo'));
     
} catch (\Exception $e) {
    return $e->getMessage();
  }     
}

public function getStates($countryId)
{
    try {

    // Fetch states where the country_id matches the selected country
    $states = StatesModel::where('country_id', $countryId)->get();

    return response()->json([
        'states' => $states
    ]);
} catch (\Exception $e) {
    return $e->getMessage();
  }
}
public function about_us(Request $request)
    {
        try {
            $countryCod = $data['country_code2'] ?? 'IN'; // Example: 'IN'
            Session::put('activecountry', 'IN');
    
            $language = app()->getLocale() == 'ar' ? 'ar' : 'en';
            $store = Stores::where('countryCode', $countryCod)->first();
            $storeId = $store->id ?? 1;
            $currency = $store->currency ?? 'INR';
            $countries=Countries::get();
            $cartItems = Cart::getContent();

            return view('front-end.about-us',compact('storeId','currency','countries','language','cartItems'));
        } catch (\Exception $e) {
            return $e->getMessage();
          }
}
public function blog(Request $request)
    {
        try {
          $countryCod = $data['country_code2'] ?? 'IN'; // Example: 'IN'
          Session::put('activecountry', 'IN');
    
            $language = app()->getLocale() == 'ar' ? 'ar' : 'en';
            $store = Stores::where('countryCode', $countryCod)->first();
            $storeId = $store->id ?? 1;
            $currency = $store->currency ?? 'INR';
            $countries=Countries::get();
            $cartItems = Cart::getContent();
            $videoInstagram=VideoGallary::where('channel','Instagram')->get();
            $videoYoutube=VideoGallary::where('channel','Youtube')->get();
            return view('front-end.blog',compact('storeId','currency','countries','language','cartItems','videoInstagram','videoYoutube'));
     
        } catch (\Exception $e) {
            return $e->getMessage();
          }
}

public function getProductPrice(Request $request)
    {
        try {

        $productPrice = ProductPrices::where('product_size_id', $request->product_size_id)
                                     ->where('store_id', $request->store_id)
                                     ->first();

        if ($productPrice) {
            $offer=$productPrice->original_price-$productPrice->offer_price;
            return response()->json(['price' => $productPrice->offer_price,'currency'=>$productPrice->currency,'original_price'=>$productPrice->original_price,'offer'=>$offer]);
        } else {
            return response()->json(['price' => 'N/A'], 404); // return error if not found
        }
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }
}
