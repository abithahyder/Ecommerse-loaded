<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SocialAuth;
use Illuminate\Support\Facades\Cache;

class SocialController extends Controller
{
    //
    public function index() {
        
        $data = socialAuth();

        return view( 'admin.social-auth', compact( 'data' ) );
    }

    public function save( Request $request ) {

        $keys = SocialAuth::all()->pluck( 'key' )->toArray();

        foreach( request()->key as $key=>$value ) {

            if( in_array( $key, $keys ) ) {
                
                $update = array(
                    'key'        => $key,
                    'value'      => $value,
                    'updated_at' => date( 'Y-m-d H:i:s', time() )
                );
                
                SocialAuth::where( 'key', $key )->update( $update );
            
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
            SocialAuth::insert( $add );
        }

        Cache::forget('socialAuth');
        return redirect()->back()->with( 'success' , 'Social Authentication save Successfully!' );
    }
}
