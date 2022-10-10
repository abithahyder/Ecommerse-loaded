<?php

namespace App\Http\Middleware;

use Closure;
use App\setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CheckForMaintenanceMode
{
    public function handle($request, Closure $next)
    {   
        if (! Cache::has( 'setting' ) ) {
            
            $setting = setting::where('id','1')->get()->toArray();
            Cache::put( 'setting', $setting , 1800 ); // 30 Minutes
        }

        $setting = Cache::get( 'setting' );

        if (!empty($setting)) {
            if($setting->maintenance_mode == '1' && Auth::user()->user_type != 'administrator'){
                return abort(503, 'This app is currently undergoing maintenance.');
            }
        }
        return $next($request);
    }
}
