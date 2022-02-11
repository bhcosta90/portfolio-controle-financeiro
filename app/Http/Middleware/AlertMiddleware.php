<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AlertMiddleware
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
        if (session('success')) {
            Alert::success(session('success'));
        }

        if (session('error')) {
            Alert::error(session('error'));
        }

        if (session('errorForm')) {
            $html = "<ul style='list-style: none;'>";
            foreach (session('errorForm') as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ul>";

            Alert::html('Error during the creation!', $html, 'error');
        }

        return $next($request);
    }
}
