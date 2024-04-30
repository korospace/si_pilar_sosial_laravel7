<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\User;
use App\Services\JwtService;

class ApiGuard
{
    protected $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header("token");

        if ($token == null) {
            return response()->json(["message" => "Unauthorized", "data" => null],401);
        }
        else {
            try {
                $payload = $this->jwtService->decode($token);

                if (time() > $payload["expired"]) {
                    return response()->json(["message" => "token expired"],401);
                }

                $user = User::find($payload['id']);
                if (!$user) {
                    return response()->json(["message" => "Unauthorized", "data" => null],401);
                }

                $request->merge(['user' => $user]);
            }
            catch (\Exception $e) {
                return response()->json(["message" => "Unauthorized", "data" => null],401);
            }
        }

        return $next($request);
    }
}
