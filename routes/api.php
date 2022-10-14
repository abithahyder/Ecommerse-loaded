<?php

use App\Http\Controllers\API\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'API\AuthController@login');
Route::post('signup', 'API\AuthController@signup');
Route::post('forgot-password', 'API\AuthController@forgot_pwd');
Route::post('reset-password', 'API\AuthController@resetPwd');

Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::get('/category','API\CategoryController@index');
    Route::get('/category/{id}','API\CategoryController@show');
    Route::post('/category','API\CategoryController@store');
    Route::get('/productcategory','API\ProductCategoryController@index');
    Route::get('/productcategory/{id}','API\ProductCategoryController@show');
    
   Route::get('/products','API\ProductController@index');
   Route::get('/product-details','API\ProductController@productdetails');
   Route::post('/add_to_wishlist','API\ProductController@addwhishlist');
   Route::post('/add_review','API\ProductController@addReview');
   Route::post('/add_to_cart','API\CartController@addCart');
   Route::post('/remove_from_cart','API\CartController@removeCartItem');
   Route::post('/change_qty','API\CartController@updatecart');
   Route::post('/delivery_charge','API\OrderController@getdeleiverycharge');
   Route::post('/verify_coupons','API\OrderController@checkcoupon');
   Route::post('/view_cart','API\CartController@viewcart');
   Route::post('/view_availability','API\ProductController@availability');
  // Route::post('/apply_coupons','API\OrderController@applycoupon');
   Route::post('/place_order','API\OrderController@add_order');
   Route::get('/order_list','API\OrderController@orderList');
   Route::post('/order_details','API\OrderController@orderDetails');
   Route::get('/products/getsku','API\ProductController@getsku');
   Route::post('/products/product_available','API\ProductController@availablepin');

    Route::post('/logout',[AuthController::class,'logout']);
});