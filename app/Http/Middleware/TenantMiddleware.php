<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $admin = [
            'login'
        ];
        
        if(substr(Route::currentRouteName(), 0, 5) === 'admin' || in_array(Route::currentRouteName(), $admin)){
            Config::set('tenancy.filesystem.asset_helper_tenancy', false);
        }

        return $next($request);
    }
}
