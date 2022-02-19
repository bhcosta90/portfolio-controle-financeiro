<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
            toastr()->success(session('success'));
        }

        if (session('error')) {
            toastr()->error(session('error'));
        }

        if (session('warning')) {
            toastr()->warning(session('warning'));
        }

        if (session('errorForm')) {
            $html = "<ul style='list-style: none;'>";
            foreach (session('errorForm') as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ul>";

            toastr()->error($html);
        }

        return $next($request);
    }
}
