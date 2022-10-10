<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PaytmWallet;


class PaytmController extends Controller
{
    //

    public function paytmPayment( Request $request ) {

        $paymentSettings = paymentSettings();

        if( ! empty( $paymentSettings ) ) {
            config( 
                [
                    "paytm.paytm-wallet.merchant_id"  => $paymentSettings['paytm_merchant_id'] ? $paymentSettings['paytm_merchant_id'] : config( "paytm.paytm-wallet.merchant_id" ), 
                    "paytm.paytm-wallet.merchant_key" => $paymentSettings['paytm_merchant_key'] ? $paymentSettings['paytm_merchant_key'] : config( "paytm.paytm-wallet.merchant_key" ),
                    "paytm.paytm-wallet.env"          => $paymentSettings['paytm_env'] ? $paymentSettings['paytm_env'] : config( "paytm.paytm-wallet.env" ),
                ]
            );
        }

        $order_id = time().rand(1,100);
        $amount   = "100";

        $payment = PaytmWallet::with( 'receive' );

        $payment->prepare([
            'order'         => $order_id,
            'user'          => $request->user,
            'mobile_number' => $request->mobile_number,
            'email'         => $request->email,
            'amount'        => $amount,
            'callback_url'  => url( 'api/payment/status' )
        ]);
        
        return $payment->receive();
    }

    public function paymentCallback() {
        
        $transaction = PaytmWallet::with( 'receive' );

        $response = $transaction->response();
        $order_id = $transaction->getOrderId();

        if( $transaction->isSuccessful() ) {

            $transaction_id = $transaction->getTransactionId();

            dd( 'Payment Successfully Paid.' );
        
        } else if ( $transaction->isFailed() ) {

            $transaction_id = $transaction->getTransactionId();

            dd( 'Payment Failed.' );
        }
    }    
}
