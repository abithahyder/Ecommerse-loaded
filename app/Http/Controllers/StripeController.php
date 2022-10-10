<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe;

class StripeController extends Controller
{
    
    // stripe payment
    public function stripe() {
        
        $paymentSettings = paymentSettings();

        return view( 'frontend.payment-gateway.stripe', compact( 'paymentSettings' ) );
    }

    public function stripePost( Request $request ) {

        $paymentSettings = paymentSettings();

        $stripe_secret = ! empty( $paymentSettings ) ? $paymentSettings['stripe_secret'] : env( 'STRIPE_SECRET' );

        Stripe\Stripe::setApiKey( $stripe_secret );
        
        try{
        
            Stripe\Charge::create ([
                "amount"      => 100,
                "currency"    => "INR",
                "source"      => request( 'stripeToken' ),
                "description" => "Test payment from Senior Experts."
            ]);
        
        } catch( \Exception $e ) {

            $body = $e->getJsonBody();
            $err  = $body['error'];
            
            return redirect()->back()->with( 'error' , $err['message'] )->withInput();
        }
        
        return redirect()->back()->with( 'success' , "Payment Successfully" );
    }


}
