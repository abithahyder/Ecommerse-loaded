<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Session;
use Redirect;

/** All Paypal Details class **/
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class PaypalController extends Controller
{
    //
    

    public function payWithPaypal() {
        return view('frontend.payment-gateway.paypal');
    }

    public function postPaymentWithpaypal( Request $request ) {

        $paymentSettings = paymentSettings();

        $paypal_conf = \Config::get('paypal');
        
        $paypal_conf['settings']['mode'] = $paymentSettings['paypal_mode'];
        $paypal_conf['client_id']        = $paymentSettings['paypal_client_id'];
        $paypal_conf['secret']           = $paymentSettings['paypal_secret'];
        
        $api_context = new ApiContext( new OAuthTokenCredential( $paymentSettings['paypal_client_id'], $paymentSettings['paypal_secret'] ) );
        $api_context->setConfig( $paypal_conf['settings'] );

        $payer = new Payer();
        $payer->setPaymentMethod( 'paypal' );

    	$item_1 = new Item();

        $item_1->setName( 'Item 1' ) /** item name **/
            ->setCurrency( 'USD' )
            ->setQuantity( 1 )
            ->setPrice( $request->get( 'amount' ) ); /** unit price **/

        $item_list = new ItemList();
        $item_list->setItems( array( $item_1 ) );

        $amount = new Amount();
        $amount->setCurrency( 'USD' )
            ->setTotal( $request->get( 'amount' ) );

        $transaction = new Transaction();
        $transaction->setAmount( $amount )
            ->setItemList( $item_list )
            ->setDescription( 'Your transaction description' );

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl( URL::route( 'paypal.status' ) ) /** Specify return URL **/
            ->setCancelUrl( URL::route( 'paypal.status' ) );

        $payment = new Payment();
        $payment->setIntent( 'Sale' )
            ->setPayer( $payer )
            ->setRedirectUrls( $redirect_urls )
            ->setTransactions( array( $transaction ) );
            
        try {    
            
            $payment->create( $api_context );
        
        } catch ( \PayPal\Exception\PPConnectionException $ex ) {
            
            if ( \Config::get( 'app.debug' ) ) {
                return redirect()->route( 'paypal.fail.msg' )->with( 'error', 'Connection timeout' );
            } else {                
                return redirect()->route( 'paypal.fail.msg' )->with( 'error', 'Some error occur, sorry for inconvenient' );
            }
        }

        foreach( $payment->getLinks() as $link ) {
            if( $link->getRel() == 'approval_url' ) {
                $redirect_url = $link->getHref();
                break;
            }
        }

        /** add payment ID to session **/
        Session::put( 'paypal_payment_id', $payment->getId() );

        if( isset( $redirect_url ) ) {
            /** redirect to paypal **/
            return Redirect::away( $redirect_url );
        }

        return redirect()->route( 'paypal.fail.msg' )->with( 'error', 'Unknown error occurred' );
    }

    public function getPaymentStatus( Request $request ) {

        /** Get the payment ID before session clear **/
        $payment_id = Session::get( 'paypal_payment_id' );
        
        /** clear the session payment ID **/
        Session::forget( 'paypal_payment_id' );
        
        if ( empty( $request->PayerID ) || empty( $request->token ) ) {
            return redirect()->route( 'paypal.fail.msg' )->with( 'error', 'There was a problem processing your payment' );
        }
        
        $paymentSettings = paymentSettings();

        $paypal_conf = \Config::get( 'paypal' );
        $api_context = new ApiContext( new OAuthTokenCredential( $paymentSettings['paypal_client_id'], $paymentSettings['paypal_secret'] ) );
        $api_context->setConfig( $paypal_conf['settings'] );

        $payment = Payment::get( $payment_id, $api_context );
        
        $execution = new PaymentExecution();
        $execution->setPayerId( $request->PayerID );
        
        /**Execute the payment **/
        $result = $payment->execute( $execution, $api_context );
        
        if ( $result->getState() == 'approved' ) { 
            
            /** it's all right **/
            /** Here Write your database logic like that insert record or value in database if you want **/

            return redirect()->route( 'paypal.success.msg' )->with( 'success', 'Your payment has been successful' );
        }

        return redirect()->route( 'paypal.fail.msg' )->with( 'error', 'Sorry, your payment failed. No charges were made.' );
    }

    public function paymentSuccess() {
        // create view for payment success
    }

    public function paymentFail() {
        // create view for payment fail
    }
}
