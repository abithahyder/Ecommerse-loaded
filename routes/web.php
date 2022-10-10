<?php

use App\Http\Controllers\Category;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\smsSettingsController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PaytmController;
use App\Http\Controllers\PaymentSettingsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

// ********** Social Media Authentication  **********
Route::get('/auth/redirect/{provider}', [SocialAuthController::class, 'redirect']);
Route::get('/callback/{provider}', [SocialAuthController::class, 'callback']);

// ********** Send Message  **********
Route::post('/send-sms', [SmsController::class, 'sendSms'])->name('sendSms');


// ********** Payment Gateway Route Start **********************************************************************

// stripe route
Route::get( '/payment/stripe', [StripeController::class, 'stripe'] );
Route::get( '/payment/stripepost', [StripeController::class, 'stripePost'] )->name( 'stripe.post' );

// paypal route
Route::get( 'paywithpaypal', [PaypalController::class, 'payWithPaypal'] )->name( 'paywithpaypal' );
Route::post( 'paypal', [PaypalController::class, 'postPaymentWithpaypal'] )->name( 'paypal');
Route::get( 'paypal', [PaypalController::class, 'getPaymentStatus'] )->name( 'paypal.status');
Route::get( 'paypal/payment-success', [PaypalController::class, 'paymentSuccess'] )->name( 'paypal.success.msg' );
Route::get( 'paypal/payment-fail', [PaypalController::class, 'paymentFail'] )->name( 'paypal.fail.msg' );

// razorpay route
Route::get( 'razorpay/payment', [RazorpayController::class, 'index'] );
Route::post( 'razorpay/payment', [RazorpayController::class, 'razorpayPayment'] )->name( 'razorpay.payment' );

// paytm route
Route::post( 'paytm/payment', [PaytmController::class, 'paytmPayment'] )->name( 'paytm.payment' );

// ********** Payment Gateway Route End **********************************************************************



// ********** Admin Panel route start **********************************************************************

Route::get('/','MainController@redirect');
Route::group([ 'middleware' => [ 'auth', 'CheckForMaintenanceMode' , 'CheckPermisssion']], function () {
    
    Route::get('/dashboard', 'HomeController@index')->name('dashboard');
    // ********** AddAdmin  **********
    // Get Method
    Route::get('/admin', 'addAdmin@index')->name('adminList');
    Route::get('/admin/getdata', 'addAdmin@getdata')->name('admindata.getdata');
    Route::get('/admin/export', 'addAdmin@export')->name('admindata.export');
    Route::get('/admin/{id}','addAdmin@index')->name('adminEdit');
    // Post Method
    Route::post('/save-admin','addAdmin@saveAdmin')->name('saveAdmin');
    Route::post('admin/delete','addAdmin@deleteAdmin')->name('adminDelete');
    Route::post('admin/status-change','addAdmin@statusChange')->name('adminStatusChange');
    Route::post('admin/notification-status','addAdmin@notification_status')->name('notification.status');

// ********** Group **********
    // Get Method
    Route::get('/group','group@index')->name('group');
    Route::get('/group/getdata', 'group@getdata')->name('group.getdata');
    Route::get('/group/export', 'group@export')->name('group.export');
    Route::get('/group/edit/{id}','group@addRole')->name('groupEdit');
    Route::get('/group/new-role','group@addRole')->name('addRole');

    // Post Method
    Route::post('/group/save','group@save')->name('savegroup');
    Route::post('/group/delete','group@deleteGroup')->name('groupDelete');

    
    // ********** Settings  **********
    // Get Method
    Route::get('/setting', 'settings@index')->name('setting');
    Route::post('/setting/save', 'settings@save')->name('setting.save');
    Route::get('/setting/notification', 'settings@notification')->name('setting.notification');
    Route::post('/setting/notification/send', 'settings@notificationSend')->name('setting.notification.send');

    Route::get('/setting/home-slider/', 'settings@homeSlider')->name('setting.home.slider');
    Route::get('/setting/home-slider/{id}', 'settings@homeSlider')->name('setting.home.slider.edit');
    Route::post('/setting/home-slider-save', 'settings@homeSliderSave')->name('setting.home.slider.save');
    Route::get('/setting/home-slider-delete/{id}', 'settings@homeSliderDelete')->name('setting.home.slider.delete');

    Route::get('/setting/delivery-charge/', 'settings@deliveryCharge')->name('setting.delivery.charge');
    Route::get('/setting/delivery-charge/{id}', 'settings@deliveryCharge')->name('setting.delivery.charge.edit');
    Route::post('/setting/delivery-charge/save', 'settings@deliveryChargeSave')->name('setting.delivery.charge.save');
    Route::get('/setting/delivery-charge-delete/{id}', 'settings@deliveryChargeDelete')->name('setting.delivery.charge.delete');
    // ********** SMTP settings  **********
    // Get Method
    Route::get('smtp', 'smtp@index')->name('smtp');
    Route::post('smtp/save', 'smtp@save')->name('smtp.save');
    Route::get('smtp/test', 'smtp@test_mail')->name('smtp.test.mail');

    // ********** Social Auth settings  **********
    // Get Method
    Route::get('social-authentication', [SocialController::class, 'index'])->name('social-authentication');
    Route::post('social/save', [SocialController::class, 'save'])->name('social.save');
    
    // ********** Payment gateway settings  **********
    // Get Method
    Route::get('payment-settings', [PaymentSettingsController::class, 'index'])->name('payment-settings');
    Route::post('payment-settings/save', [PaymentSettingsController::class, 'save'])->name('payment-settings.save');

    // ********** SMS settings  **********
    // Get Method
    Route::get('sms-settings', [smsSettingsController::class, 'index'])->name('sms-settings');
    Route::post('sms-settings/save', [smsSettingsController::class, 'save'])->name('sms-settings.save');

//**** Category*****/

Route::get('category-management', 'category@index')->name('category.list');
Route::get('category/add', 'Category@add')->name('category.add');
Route::get('category/edit/{id}', 'Category@add')->name('category.edit');

Route::post('category/get-data', 'Category@getData')->name('category.data');
Route::post('category/save', 'Category@store')->name('category.save');
Route::post('category/delete/','Category@destroy')->name('category.delete');
 // ********** Client  **********
    // Get Method
    Route::get('user-management', 'Client@index')->name('client.list');
    Route::get('user/add', 'Client@add')->name('client.add');
    Route::get('user/edit/{id}', 'Client@add')->name('client.edit');

    Route::post('user/get-data', 'Client@getData')->name('client.data');
    Route::post('user/save', 'Client@save')->name('client.save');
    Route::post('user/delete','Client@delete')->name('client.delete');
    Route::post('user/search','Client@search')->name('client.search');

    // ********** coupon  **********
    // Get Method
    Route::get('coupon-management', 'CouponController@index')->name('coupon.list');
    Route::get('coupon/add', 'CouponController@create')->name('coupon.add');
    Route::get('coupon/edit/{id}', 'CouponController@create')->name('coupon.edit');

    Route::post('coupon/get-data', 'CouponController@getdata')->name('coupon.data');
    Route::post('coupon/save', 'CouponController@store')->name('coupon.save');
    Route::post('coupon/delete','CouponController@destroy')->name('coupon.delete');

//**** Product ****/
Route::get('products', 'ProductController@index')->name('product.list');
Route::get('product/add', 'ProductController@add')->name('product.add');
Route::get('product/edit/{id}', 'ProductController@add')->name('product.edit');

Route::post('product/get-data', 'ProductController@getData')->name('product.data');
Route::post('product/sub-category','ProductController@subCategory')->name('product.sub.category');
Route::post('product/image-delete','ProductController@imageDelete')->name('product.image.delete');

Route::post('product/save', 'ProductController@save')->name('product.save');
Route::post('product/delete','ProductController@delete')->name('product.delete');
Route::post('product/combinations-varient','ProductController@variantList')->name('product.variant.list');
Route::post('product/update-sku','ProductControllers@updateSku')->name('product.update.sku');
Route::post('product/delete-variant','ProductController@deleteVariant')->name('product.delete.variant');

Route::get('sub-category','SubCategory@index')->name('subcategory.list');
Route::get('sub-category/add', 'SubCategory@add')->name('subcategory.add');
Route::get('sub-category/edit/{id}', 'SubCategory@add')->name('subcategory.edit');

Route::post('sub-category/get-data', 'SubCategory@getdata')->name('subcategory.data');
Route::post('sub-category/save', 'SubCategory@store')->name('subcategory.save');
Route::post('sub-category/delete/','SubCategory@delete')->name('subcategory.delete');
//****Order management */
Route::get('order-management', 'OrderControllers@index')->name('order.list');
    Route::get('order/details/{id}', 'OrderControllers@details')->name('order.edit');
    
    Route::post('order/get-data', 'OrderControllers@getData')->name('order.data');
    Route::post('order/status-update', 'OrderControllers@statusUpdate')->name('order.status.update');

});
// ********** Admin Panel route end **********************************************************************
Route::get('/main','MainController@index');
Route::get('/view_cart','MainController@view_cart');
Route::get('/remove_cart/{id}','MainController@remove_cart');
Route::get('/check_out','MainController@checkout')->name('checkout');
Route::get('/cash_order','MainController@cash_order')->name('placeorder2');
Route::get('delivered/{id}','MainController@delivered');
Route::get('paid/{id}','MainController@paid');
Route::post('/add_to_cart/{id}','MainController@add_to_cart');
Route::post('/checkout/save','MainController@checkoutsave')->name('checkout.save');