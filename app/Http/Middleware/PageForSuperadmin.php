<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class PageForSuperadmin
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
        $privilege = $request->user->level_id;

        if (!in_array($privilege, [1])) {
            return Redirect::to('dashboard');
        }

        return $next($request);

    }
}
