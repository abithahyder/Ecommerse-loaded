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
    // Route::get('/products',[ProductController::class,'index']);

    // Route::get('/products/{id}',[ProductController::class,'show']);
    Route::get('/category','API\CategoryController@index');
    Route::get('/category/{id}','API\CategoryController@show');
    Route::post('/category','API\CategoryController@store');
    Route::get('/productcategory','ProductCategoryController@index');
    Route::get('/productcategory/{id}','ProductCategoryController@show');
    Route::post('/productcategory',[ProductCategoryController::class,'store']);

    Route::post('/products',[ProductController::class,'store']);
    Route::put('/products/{id}/update',[ProductController::class,'update']);
    Route::delete('/products/{id}',[ProductController::class,'destroy']);
    Route::put('/category/{id}/update','API\CategoryController@update');
    Route::delete('/category/{id}','API\CategoryController@destroy');
    Route::put('/productcategory/{id}/update',[ProductCategoryController::class,'update']);
    Route::delete('/productcategory/{id}',[ProductCategoryController::class,'destroy']);

    Route::get('/cart',[CartController::class,'index']);
    Route::get('/products/{title}',[ProductController::class,'searchpro']);

    Route::post('/logout',[AuthController::class,'logout']);
});