<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class permission
{
    public function handle($request, Closure $next, $action, $page)
    {   
        return $next($request);
    }
}