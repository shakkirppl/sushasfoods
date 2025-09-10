<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BOMController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\WorkCenterController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\StockReturnController;
use App\Http\Controllers\DamageController;
use App\Http\Controllers\ProductPriceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VideoGallaryController;
use App\Http\Controllers\YourOrdersController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\WhatsappController;
use App\Http\Controllers\WhatsappOrderReportController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\VisitReportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AboutUsImageController;
use App\Http\Controllers\ProductCountryController;
use App\Http\Controllers\WhatsappOrderController;
use App\Http\Controllers\AllOrderController;
use App\Http\Controllers\OrderDashboardController;
use App\Http\Controllers\CertificateImageController;
use App\Http\Controllers\HomeVideoGallaryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WhatInFarmController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/clear', function() {
    //   $mytime = Carbon\Carbon::now();
    //  return $mytime->toDateTimeString();
    $exitCode = Artisan::call('cache:clear');
     $exitCode = Artisan::call('config:clear');
     $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('config:cache');

    return '<h1>cleared</h1>';
});
Route::get('/admin-login', function () {
    return view('login');
});
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');
Route::post('/validate-user', [AuthController::class, 'validate_user'])->name('validate_user');
Route::post('/admin-login-post', [RegisterController::class, 'admin_login_post'])->name('admin-login-post');

Route::post('/sign-out', [AuthController::class, 'signOut'])->name('sign-out');

Route::middleware(['setCountryStore'])->group(function () {
    // Route::get('/', [HomeController::class, 'index']);
    // Route::get('/combo', [HomeController::class, 'combo']);
// Route::get('/get-product-price', [HomeController::class, 'getProductPrice']);
// Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
// Route::get('/guest-checkout', [CheckoutController::class, 'guest_checkout'])->name('guest-checkout');

// Route::get('/cart/add', [CartController::class, 'addToCart'])->name('cart/add');
// Route::get('/cart/count', [CartController::class, 'cartCount'])->name('cart.count');
// Route::post('/cart/add/home', [CartController::class, 'homeAddToCart'])->name('cart/add/home');
// Route::get('lang/change/{id}',[HomeController::class, 'lang_change']);
// Route::post('/set-country', [HomeController::class, 'setCountry'])->name('set.country');

// Route::get('/product-view/{id}', [ProductDetailController::class, 'view'])->name('product.view');
// Route::get('/product.reviews/{id}', [ProductDetailController::class, 'reviews'])->name('product.reviews');

// Route::get('/view-cart', [CartController::class, 'view_cart']);
// Route::post('/login-user', [RegisterController::class, 'login_user'])->name('login-user');
// Route::post('/register-user', [RegisterController::class, 'register_user'])->name('register-user');
// Route::get('/user-login', [RegisterController::class, 'login']);
// Route::get('/user-register', [RegisterController::class, 'register']);
// Route::get('/address.new', [RegisterController::class, 'address_new'])->name('address.new');


// Route::post('/save-orders', [CheckoutController::class, 'store_order_details'])->name('save.details');
// Route::post('/save-orders-guest', [CheckoutController::class, 'store_guest_order_details'])->name('save.details.guest');

// Route::get('/account', [RegisterController::class, 'account']);
// Route::get('/your-orders', [YourOrdersController::class, 'orders']);
// Route::get('/return-items/{id}', [YourOrdersController::class, 'return_item']);
// Route::get('/re-order/{id}', [CheckoutController::class, 're_order']);
// Route::get('/cancel-order/{id}', [CheckoutController::class, 'cancel_order']);

// Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
// Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');

// Route::get('/your-profile', [YourOrdersController::class, 'yourprofile']);
// Route::post('/return.item', [YourOrdersController::class, 'return_item_store'])->name('return.item');
// Route::post('/calculate-shipping-charge', [CheckoutController::class, 'calculateShippingCharge'])->name('calculate.shipping.charge');
// Route::get('/get-states/{countryId}', [HomeController::class, 'getStates'])->name('get-states');


// Route::get('/order-confirmation/{id}', [CheckoutController::class, 'order_confirmation'])->name('order-confirmation');

// Route::get('/product-review/{id}', [ProductDetailController::class, 'product_review'])->name('product-review');
// Route::get('/customer-address', [RegisterController::class, 'customer_address'])->name('customer-address');
// Route::post('/save.review', [ReviewController::class, 'save_review'])->name('save.review');


    // Your routes here
});

// Route::post('/update.billing-address', [RegisterController::class, 'update_billing_address'])->name('update.billing-address');
Route::get('/products-status', [ProductCountryController::class, 'index']);
Route::get('/products-size-status', [ProductCountryController::class, 'size_index']);
Route::post('/product/toggle-status', [ProductCountryController::class, 'toggleProductStatus']);
Route::post('/product-size/toggle-status', [ProductCountryController::class, 'toggleProductSizeStatus']);

Route::get('about-us', [HomeController::class, 'about_us']);
Route::get('blog', [HomeController::class, 'blog']);

Route::post('/add.shipping-address', [RegisterController::class, 'add_shipping_address'])->name('add.shipping-address');
Route::post('/add.new.shipping-address', [RegisterController::class, 'add_new_shipping_address'])->name('add.new.shipping-address');

Route::post('/update.shipping-address', [RegisterController::class, 'update_shipping_address'])->name('update.shipping-address');

Route::get('address.edit/{id}', [RegisterController::class, 'address_edit'])->name('address.edit');
Route::get('address.remove/{id}', [RegisterController::class, 'address_remove'])->name('address.remove');
Route::get('address.set.default/{id}', [RegisterController::class, 'address_set_default'])->name('address.set.default');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('dashboard', [DashboardController::class,'dashboard']);

    Route::get('/update-password', [ProfileController::class, 'update_password'])->name('update-password');
    Route::post('password-update.store', [ProfileController::class, 'password_update_store'])->name('password-update.store');
    
    Route::resource('shift', ShiftController::class);
    Route::resource('products', ProductController::class);
    Route::resource('video-gallary', VideoGallaryController::class);
    Route::resource('testimonial', TestimonialController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('what-in-farm', WhatInFarmController::class);
Route::delete('what-in-farm/image/{id}', [WhatInFarmController::class, 'deleteImage'])
    ->name('what-in-farm.image.delete');


    Route::resource('about-us-images', AboutUsImageController::class);
    Route::resource('certificate', CertificateImageController::class);
    Route::resource('home-video-gallary', HomeVideoGallaryController::class);
    Route::get('news-letter', [AboutUsImageController::class, 'news_letter']);
    
    Route::post('products-sku.store', [ProductController::class, 'storeSku'])->name('products-sku.store');
    Route::delete('product-sku/{id}', [ProductController::class, 'destroySku'])->name('product-sku.destroy');
    Route::post('products.update', [ProductController::class, 'updateProduct'])->name('products.update.pro');
    Route::post('products-image.store', [ProductController::class, 'updateImages'])->name('products-image.store');
    Route::post('products-main-image.store', [ProductController::class, 'products_main_image_store'])->name('products-main-image.store');
    Route::post('products-detail-image.store', [ProductController::class, 'products_detail_image_store'])->name('products-detail-image.store');
    Route::post('products-unit-image.store', [ProductController::class, 'products_unit_image_store'])->name('products-unit-image.store');
    Route::post('products-shipping-charge-india.store', [ProductController::class, 'products_shipping_charge_india_store'])->name('products-shipping-charge-india.store');
    
    
    Route::get('delete.image/{id}', [ProductController::class, 'deleteImage'])->name('delete.image');
   
    Route::get('products.addon/{id}', [ProductController::class, 'addon']);
    Route::get('products.edit/{id}', [ProductController::class, 'edit']);
    Route::get('products.show/{id}', [ProductController::class, 'show']);

    // Route::get('products.image/{id}', [ProductController::class, 'image']);

    Route::get('products.main.image.change/{id}', [ProductController::class, 'mainImage']);
    Route::get('products.detail.image.change/{id}', [ProductController::class, 'detailImage']);
    Route::get('products-unit.image.change/{id}', [ProductController::class, 'unitImage']);
    Route::get('products-unit.shipping.charge/{id}', [ProductController::class, 'shippingCharge']);
    
    // product price

    
    Route::get('/product-price-manage', [ProductPriceController::class, 'product_price_manage'])->name('product-price-manage');
    Route::get('products.add-update-price/{id}', [ProductPriceController::class, 'update_price']);
    Route::post('products/save-price', [ProductPriceController::class, 'save_price'])->name('products.save-price');

    Route::get('/product-prices', [ProductPriceController::class, 'index'])->name('product-prices.index');
    Route::get('/product-prices/create', [ProductPriceController::class, 'create'])->name('product-prices.create');
    Route::post('/product-prices', [ProductPriceController::class, 'store'])->name('product-prices.store');
    Route::get('/product-prices/{id}/edit', [ProductPriceController::class, 'edit'])->name('product-prices.edit');
    Route::put('/product-prices/{id}', [ProductPriceController::class, 'update'])->name('product-prices.update');





Route::get('order-view/{id}', [OrderController::class, 'order_view'])->name('order_view');

Route::post('order.status.update', [OrderController::class, 'updateStatus'])->name('order.status.update');
Route::put('/order/{order}/update-delivery-date', [OrderController::class, 'updateDeliveryDate'])->name('order.updateDeliveryDate');


Route::get('return-item/collected/{id}', [OrderController::class, 'return_item_collected'])->name('return-item/collected');


Route::get('pending-orders', [OrderController::class, 'show_pending_orders']);
Route::get('accepted-orders', [OrderController::class, 'show_accepted_orders']);
Route::get('packed-orders', [OrderController::class, 'show_packed_orders']);
Route::get('delivered-orders', [OrderController::class, 'show_delivered_orders']);


// all orders  
Route::get('all-pending-orders', [AllOrderController::class, 'show_pending_orders']);
Route::get('all-accepted-orders', [AllOrderController::class, 'show_accepted_orders']);
Route::get('all-packed-orders', [AllOrderController::class, 'show_packed_orders']);
Route::get('all-delivered-orders', [AllOrderController::class, 'show_delivered_orders']);
Route::get('orders/tracking', [AllOrderController::class, 'orders_tracking'])->name('orders.tracking');


Route::get('canceled-order', [OrderController::class, 'canceled_order']);
Route::get('return-items-pending', [OrderController::class, 'return_items_pending']);

Route::get('review-pending', [ReviewController::class, 'review_pending']);
Route::get('review-active', [ReviewController::class, 'review_active']);
Route::get('review-block', [ReviewController::class, 'review_block']);
Route::get('review-active/{id}', [ReviewController::class, 'active']);
Route::get('review-active/{id}', [ReviewController::class, 'block']);

Route::get('sales-report/date-wise', [ReportController::class, 'salesDateWiseReport'])->name('sales-report/date-wise');
Route::get('sales-report/country-wise', [ReportController::class, 'salesCountryWiseReport'])->name('sales-report/country-wise');
Route::get('sales-report/area-wise', [ReportController::class, 'salesAreaWiseReport'])->name('sales-report/area-wise');
Route::get('sales-report/product-wise', [ReportController::class, 'salesProductWiseReport'])->name('sales-report/product-wise');

Route::get('/most-repurchased-customers', [ReportController::class, 'mostRepurchasedCustomers'])->name('reports.mostRepurchasedCustomers');
Route::get('/most-repurchased-products', [ReportController::class, 'mostRepurchasedProducts'])->name('reports.mostRepurchasedProducts');

Route::get('reports/most-moving', [ReportController::class, 'mostMovingItems'])->name('reports.most-moving');
Route::get('reports/least-moving', [ReportController::class, 'leastMovingItems'])->name('reports.least-moving');
Route::get('reports/sales/{id}', [ReportController::class, 'viewSalesDetail'])->name('reports.sales.detail');
Route::get('/order/invoice/{id}', [OrderController::class, 'printInvoice'])->name('order.invoice');
Route::get('/order-dashboard', [OrderDashboardController::class, 'index'])->name('order.dashboard');

Route::get('/send-mail', [AllOrderController::class, 'sendmail']);
Route::get('/order/invoice/{id}', [OrderController::class, 'printInvoice'])->name('order.invoice');
Route::post('/orders/print', [OrderController::class, 'printSelected'])->name('orders.print');
}); 

require __DIR__.'/auth.php';
