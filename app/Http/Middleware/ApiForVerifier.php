<?php

namespace App\Http\Middleware;

use Closure;

class ApiForVerifier
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

        if (!in_array($privilege, [1, 2])) {

            return response()->json(["message" => "Permission Denied"],401);

        }

        return $next($request);

    }
}
