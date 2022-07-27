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
        try {
            $response = $next($request);
            
            if ($response instanceof \Illuminate\Http\Response) {
                return $response;
            }

            list($firstNameRoute) = explode('.', $request->route()->getName());
            
            if ($firstNameRoute === 'admin') {
                return match (strtoupper($request->getMethod())) {
                    'GET' => response(view($request->route()->getName(), $response->original)),
                    'POST' => $this->methodPost($response),
                    'PUT' => $this->methodPost($response),
                    'DELETE' => redirect()->back()->with('success', $response->original['message'] ?? null),
                    default => $response,
                };
            }
            
            return $response;
            
        } catch(Throwable $e) {
            throw $e;
        }
    }

    private function methodPost($response)
    {
        $data = $response->original;

        if (!empty($data['redirect'])) {
            return redirect($data['redirect'])->with('success', $data['message'] ?? null);
        }
    }
}
