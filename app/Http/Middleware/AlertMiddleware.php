<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AlertMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($session = session('success')) {
            // toast(__($session), 'success');
            alert()->success(__('Success'), __($session));
        }
        if ($session = session('error')) {
            // toast(__($session), 'error');
            alert()->error(__('Error'), __($session));
        }
        return $next($request);
    }
}
