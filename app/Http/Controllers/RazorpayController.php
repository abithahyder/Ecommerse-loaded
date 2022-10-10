<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Session;
use Exception;

class RazorpayController extends Controller
{
    //
    public function index() {        
        
        $paymentSettings = paymentSettings();

        return view( 'frontend.payment-gateway.razorpay', compact( 'paymentSettings' ) );
    }

    public function razorpayPayment( Request $request ) {
        
        $paymentSettings = paymentSettings();

        $input = $request->all();

        $razorpay_key    = $paymentSettings["razorpay_key"] ? $paymentSettings["razorpay_key"] : '';
        $razorpay_secret = $paymentSettings["razorpay_secret"] ? $paymentSettings["razorpay_secret"] : '';

        $api = new Api( $razorpay_key, $razorpay_secret );
  
        $payment = $api->payment->fetch( $input['razorpay_payment_id'] );
  
        if( count( $input )  && ! empty( $input['razorpay_payment_id'] ) ) {
            try {
 
                $response = $api->payment->fetch( $input['razorpay_payment_id'] )->capture( array( 'amount'=>$payment['amount'] ) ); 
  
            } catch (Exception $e) {
                
                return redirect()->back()->with( "error", $e->getMessage() );
            }
        }
        
        return redirect()->back()->with( "success", 'Payment successful' );
    }


}
