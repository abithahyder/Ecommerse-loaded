<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\User;

class SocialAuthController extends Controller
{
    //

    public function redirect( $provider ) {

        return Socialite::driver( $provider )->redirect();
    }

    public function callback( $provider ) {

        $SocialSettings = socialAuth();

        if( ! empty( $SocialSettings ) ) {
            config( 
                [
                    "services.google.client_id"       => $SocialSettings['google_client_id'] ? $SocialSettings['google_client_id'] : config("services.google.client_id"), 
                    "services.google.client_secret"   => $SocialSettings['google_client_secret'] ? $SocialSettings['google_client_secret'] : config("services.google.client_secret"),
                    "services.google.redirect"        => $SocialSettings['google_redirect_url'] ? $SocialSettings['google_redirect_url'] : config("services.google.client_id"),
                    "services.facebook.client_id"     => $SocialSettings['fb_client_id'] ? $SocialSettings['fb_client_id'] : config("services.facebook.client_id"), 
                    "services.facebook.client_secret" => $SocialSettings['fb_client_secret'] ? $SocialSettings['fb_client_secret'] : config("services.facebook.client_secret"),
                    "services.facebook.redirect"      => $SocialSettings['fb_redirect_url'] ? $SocialSettings['fb_redirect_url'] : config("services.facebook.client_id"), 
                    "services.linkedin.client_id"     => $SocialSettings['linkedin_client_id'] ? $SocialSettings['linkedin_client_id'] : config("services.linkedin.redirect"),
                    "services.linkedin.client_secret" => $SocialSettings['linkedin_client_secret'] ? $SocialSettings['linkedin_client_secret'] : config("services.linkedin.client_secret"),
                    "services.linkedin.redirect"      => $SocialSettings['linkedin_redirect_url'] ? $SocialSettings['linkedin_redirect_url'] : config("services.linkedin.redirect"),
                ]
            );
        }
               
        try {
            
            $getInfo = Socialite::driver( $provider )->stateless()->user();

            $user = $this->createUser( $getInfo, $provider );
            
            auth()->login( $user );
                
            return redirect()->route( 'login' );  

        } catch( Throwable $e ) {
           
            return redirect()->route( 'login' );

        }
    }

    function createUser( $getInfo, $provider ) {
 
        if( $provider == 'linkedin' ) {
            $user        = User::where( 'linkedin_id', $getInfo->id )->orWhere( 'email', $getInfo->email )->first();
            $linkedin_id = $getInfo->id;
            $facebook_id = $user->facebook_id;
            $google_id   = $user->google_id;
        } 
        
        if( $provider == 'facebook' ) {
            $user        = User::where( 'facebook_id', $getInfo->id )->orWhere( 'email', $getInfo->email )->first();
            $linkedin_id = $user->linkedin_id;
            $facebook_id = $getInfo->id;
            $google_id   = $user->google_id;
        } 
        
        if( $provider == 'google' ) {
            $user        = User::where( 'google_id', $getInfo->id )->orWhere( 'email', $getInfo->email )->first();
            $linkedin_id = $user->linkedin_id;
            $facebook_id = $getInfo->id;
            $google_id   = $getInfo->id;
        } 
 
        if ( ! $user ) {
            
            $user = User::create([
                'name'        => $getInfo->name,
                'email'       => $getInfo->email,
                'status'      => 'active',
                'linkedin_id' => $linkedin_id,
                'facebook_id' => $facebook_id,
                'google_id'   => $google_id
            ]);
        
        } else {

            $data = array(
                'linkedin_id' => $linkedin_id,
                'facebook_id' => $facebook_id,
                'google_id'   => $google_id,
            );

            User::where( 'email', $getInfo->email )->update( $data );
        }
        
        return $user;
    }

}
