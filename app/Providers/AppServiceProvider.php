<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        if (!\App::runningInConsole()) {

            if (!Cache::has( 'setting' ) ) {
                Cache::put( 'setting', setting::first(), 1800 ); // 30 Minutes
            }
            View::share('setting', Cache::get( 'setting') );
        }
    }
}
