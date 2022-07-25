<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Throwable;

class WebMiddleware
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
        $response = $next($request);
        if (!empty($_GET['r'])) {
            return $response;
        }
        list($admin) = explode('.', $request->route()->getName());

        if ($admin === 'admin') {
            return match (strtoupper($request->getMethod())) {
                'GET' => response(view($request->route()->getName(), $response->original)),
                'POST' => $this->methodPost($response),
                'PUT' => $this->methodPost($response),
                'DELETE' => $this->methodPost($response),
                default => $response,
            };
        }
        return $response;
    }

    private function methodPost($response)
    {
        $data = $response->original;

        if (!empty($data['redirect'])) {
            return redirect($data['redirect'])->with('success', $data['message'] ?? null);
        }
    }
}