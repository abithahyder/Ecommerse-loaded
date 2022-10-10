<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentSettings;
use Illuminate\Support\Facades\Cache;

class PaymentSettingsController extends Controller
{
    //
    public function index() {
        
        $data = paymentSettings();

        return view( 'admin.payment-settings', compact( 'data' ) );
    }

    public function save( Request $request ) {

        $keys = PaymentSettings::all()->pluck( 'key' )->toArray();

        foreach( request()->key as $key=>$value ) {

            if( in_array( $key, $keys ) ) {
                
                $update = array(
                    'key'        => $key,
                    'value'      => $value,
                    'updated_at' => date( 'Y-m-d H:i:s', time() )
                );
                
                PaymentSettings::where( 'key', $key )->update( $update );
            
            } else {

                $add[] = array(
                    'key'        => $key,
                    'value'      => $value,
                    'created_at' => date( 'Y-m-d H:i:s', time() ),
                    'updated_at' => date( 'Y-m-d H:i:s', time() )
                );

            }

        }

        if( ! empty( $add ) ) {
            PaymentSettings::insert( $add );
        }

        Cache::forget( 'paymentSettings' );
        return redirect()->back()->with( 'success' , 'Payment Gateway Settings save Successfully!' );
    }
}
