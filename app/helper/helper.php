<?php

use App\user_groups;
use App\SocialAuth;
use App\smsSettings;
use App\PaymentSettings;
use Aws\CommandPool;
use App\user_permissions;

// use Exception;
use Aws\Ses\SesClient;
use GuzzleHttp\Client;
use Aws\ResultInterface;
use Aws\CommandInterface;
use Illuminate\Http\Request;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Exception\GuzzleException;
use WpOrg\Requests\Transport\Curl;

function check_permission( $routes = null ) {
	
	$user = Auth::user();

	if ( $user->user_type == 'administrator' ) {
		return true;
	}

	$group_id = $user->user_group;

	$data = user_groups::find( $group_id );
	
	$permission = explode(',', $data['ug_permissions']);
	
	if ( ! empty( $routes ) ) {
		
		if ( in_array( $routes, $permission ) ) {
			return true;
		} else {
			return false;
		}
	
	} else {
		
		$route = Route::current();
		
		if ( $route->action['namespace'] == 'App\Http\Controllers' && ! empty( $route->action['as'] ) ) {
			
			if ( in_array( $route->uri, $permission ) ) {
				return true;
			} elseif ( in_array( $route->action['as'], $permission ) ) {
				return true;
			}
		}
	}
	return false;
}



function get_routes() {

	$routes    = Route::getRoutes();
	$routeList = [];
	
	foreach ( $routes as $key => $value ) {
		
		if ( $value->action['namespace'] == 'App\Http\Controllers' && ! empty( $value->action['as'] ) && empty( preg_match( "/generated::/" , $value->action['as'] ) ) ) {
			if ( ! in_array( $value->action['as'], $routeList ) ) {
				$routeList[] = $value->action['as'];
			}
		}
	}
	
	return collect( $routeList )->sort() ?? [];
}

function socialAuth() {

	if ( Cache::has( 'socialAuth' ) ) {
		return Cache::get( 'socialAuth' );
	}

    $data 		= SocialAuth::get();
	$socialAuth = array();

	if( ! empty( $data ) ) {
		foreach( $data as $key ) {
			$socialAuth[$key['key']] = $key['value'];
		}
	}
    
    Cache::put( 'socialAuth', $socialAuth, 1800 );
    return $socialAuth;
}

function smsSettings() {

	if ( Cache::has( 'smsSettings' ) ) {
		return Cache::get( 'smsSettings' );
	}

    $data 		 = smsSettings::get();
	$smsSettings = array();

	if( ! empty( $data ) ) {
		foreach( $data as $key ) {
			$smsSettings[$key['key']] = $key['value'];
		}
	}
    
    Cache::put( 'smsSettings', $smsSettings, 1800 );
    return $smsSettings;
}

function paymentSettings() {

	if ( Cache::has( 'paymentSettings' ) ) {
		return Cache::get( 'paymentSettings' );
	}

    $data 			 = PaymentSettings::get();
	$paymentSettings = array();

	if( ! empty( $data ) ) {
		foreach( $data as $key ) {
			$paymentSettings[$key['key']] = $key['value'];
		}
	}
    
    Cache::put( 'paymentSettings', $paymentSettings, 1800 );
    return $paymentSettings;
}
function cache_permission()
{
	$group_id = Auth::user()->user_group;

	if ( Cache::has( 'permission'.$group_id ) ) {
		return Cache::get( 'permission'.$group_id );
	}
	
	$res = user_permissions::where('up_related_id',$group_id)->get()->toArray();
	foreach ($res as $key => $value) {
		$new[$value['up_name']] = array( 
			'up_view'   => $value['up_view'],
			'up_create' => $value['up_create'],
			'up_edit'   => $value['up_edit'],
			'up_delete' => $value['up_delete'],
		);
	}

	Cache::put( 'permission'.$group_id, $new, 1800 ); // 30 Minutes
	return $new;
	
}

// function send_push_notification($data = null,$ios = null){
// 	$key = env('FIREBASE_KEY');
// 	if( $key ){
// 		if($ios){
// 			$response = Curl::to('https://fcm.googleapis.com/fcm/send')
// 						->withData(json_encode($ios))
// 						->withHeader('Authorization:key='.$key.'')
// 						->withHeader('Content-Type:application/json')
// 						->post();
// 		}
// 		return  $response = Curl::to('https://fcm.googleapis.com/fcm/send')
// 					->withData(json_encode($data))
// 					->withHeader('Authorization:key='.$key.'')
// 					->withHeader('Content-Type:application/json')
// 					->post();
// 	}
// }
function orderStatusList($key = null)
{
	$list =  array(
		'pending' => 'Pending',
		'processing' => 'Processing',
		'on_hold' => 'On hold',
		'completed' => 'Completed',
		'cancelled' => 'Cancelled',
		'refunded' => 'Refunded',
		'failed' => 'Failed',
	);

	if( !empty($key) ){
		return isset($list[$key]) ? $list[$key]  : null ;
	}
	return $list;
}


function applyCoupon( $coupon, $cart_total )
{	
	try {
		validate_coupon_minimum_amount($coupon,$cart_total);
		$total_discount = 0;
		if ($coupon->cm_discount_type == 'percentage') {
			$total_discount = ($cart_total * ($coupon->cm_amount / 100));
		}elseif($coupon->cm_discount_type == 'amount'){
			$total_discount = $coupon->cm_amount;
		}
		if ( ( $coupon->cm_discount_type == 'percentage' ) && ( $total_discount > $coupon->cm_maximum_amount ) ) {
			$total_discount = $coupon->cm_maximum_amount;
		}
		return $total_discount;
		
	} catch (Exception $e) {
		throw new Exception($e->getMessage()); 
	}
}

function validate_coupon_minimum_amount($coupon = null,$cart_total = Null)
{   
	if($cart_total >= $coupon->cm_minimum_amount){
		return true;
	}
	throw new Exception('The minimum spend for this coupon.');
}

