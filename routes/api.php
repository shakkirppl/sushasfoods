<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerAddressController;
use App\Http\Controllers\Api\SingleViewApiController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/customer.store', [CustomerApiController::class, 'customer_store']);
Route::post('/customer.update', [CustomerApiController::class, 'user_update']);
Route::post('/customer.detail', [CustomerApiController::class, 'customer_detail']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/user', [ApiAuthController::class, 'user']);

      Route::get('addresses', [CustomerAddressController::class, 'index']);
    Route::post('addresses', [CustomerAddressController::class, 'store']);
    Route::put('addresses/{id}', [CustomerAddressController::class, 'update']);
    Route::delete('addresses/{id}', [CustomerAddressController::class, 'destroy']);
    Route::post('addresses/{id}/default', [CustomerAddressController::class, 'setDefault']);
    Route::post('store_order_user', [CheckoutController::class, 'store_order_user']);
    Route::get('/orders/{user_id}', [OrderController::class, 'getOrders']);
    Route::get('/order/{order_id}/items', [OrderController::class, 'getOrderItems']);
    Route::post('/order/reorder/{order_id}', [OrderController::class, 'reorder']);
    Route::post('/order/cancel/{order_id}', [OrderController::class, 'cancelOrder']);
    Route::post('/order/item/return', [OrderController::class, 'returnItem']);
    
});
// change last
Route::get('/homepage', [HomeApiController::class, 'index']);
Route::get('/products/search', [HomeApiController::class, 'searchProducts']);

Route::get('/product/{id}', [HomeApiController::class, 'show']);
Route::get('categories-with-products', [CategoryController::class, 'categoriesWithProducts']);
Route::get('categories/{id}/products', [CategoryController::class, 'productsByCategory']);
Route::get('/product-reviews', [HomeApiController::class, 'productReviews']);

Route::get('/video-gallary', [SingleViewApiController::class, 'videoGallary']);
Route::get('/testimonial', [SingleViewApiController::class, 'testimonial']);
Route::get('/whatInFarms', [SingleViewApiController::class, 'whatInFarms']);
Route::get('/whatInFarms-viewall', [SingleViewApiController::class, 'whatInFarms_view_all']);
Route::get('/whatInFarms-Full', [SingleViewApiController::class, 'whatInFarmsFull']);

Route::get('/whatInFarms-most-cultivated-crops', [SingleViewApiController::class, 'whatInFarms_most_cultivated']);
Route::get('/whatInFarms-most-cultivated-crops-view-all', [SingleViewApiController::class, 'whatInFarms_most_cultivated_view_all']);
Route::get('/whatInFarms-Category', [SingleViewApiController::class, 'whatInFarms_Category']);



