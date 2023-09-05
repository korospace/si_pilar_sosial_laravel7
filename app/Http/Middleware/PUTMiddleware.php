<?php

namespace App\Http\Middleware;

use Closure;

class PUTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() == 'PUT') {
            _methodParser('params');
            global $params;

            $request->merge($params);
        }

        return $next($request);
    }
}
