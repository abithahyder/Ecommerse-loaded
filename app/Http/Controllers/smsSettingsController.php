<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\smsSettings;
use Illuminate\Support\Facades\Cache;

class smsSettingsController extends Controller
{
    //
    //
    public function index() {
        
        $data = smsSettings();

        return view( 'admin.sms-settings', compact( 'data' ) );
    }

    public function save( Request $request ) {

        $keys = smsSettings::all()->pluck( 'key' )->toArray();

        foreach( request()->key as $key=>$value ) {

            if( in_array( $key, $keys ) ) {
                
                $update = array(
                    'key'        => $key,
                    'value'      => $value,
                    'updated_at' => date( 'Y-m-d H:i:s', time() )
                );
                
                smsSettings::where( 'key', $key )->update( $update );
            
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
            smsSettings::insert( $add );
        }

        Cache::forget( 'smsSettings' );
        return redirect()->back()->with( 'success' , 'SMS Settings save Successfully!' );
    }
}
