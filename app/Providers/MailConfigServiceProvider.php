<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\smtp_setting;
use Config;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (!\App::runningInConsole()) {
            if ( !Cache::has('smtp') ) {
                Cache::put('smtp', smtp_setting::get()->first(), 1800 ); // 30 Minutes
            }
            
            $emailServices = Cache::get('smtp');
            
            if ( ! empty( $emailServices ) ) {

                $config = array(
                    'driver'     => $emailServices->ss_mailer,
                    'host'       => $emailServices->ss_host,
                    'port'       => $emailServices->ss_port,
                    'username'   => $emailServices->ss_uname,
                    'password'   => $emailServices->ss_pwd,
                    'encryption' => $emailServices->ss_encryption,
                    'from'       => array( 'address' => $emailServices->ss_from_address, 'name' => $emailServices->ss_from_name ),
                );

                Config::set('mail', $config);

            }
        }

    }
}
