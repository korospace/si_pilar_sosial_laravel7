<?php

namespace App\Services\Impl;

use App\Services\LoginService;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;

class LoginServiceImpl implements LoginService
{

    protected $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Login Logic
     */
    public function loginWithCookie(LoginRequest $request): JsonResponse
    {
        /* get user by email */
        $user = User::where('email', $request->email)->first();

        if (is_null($user)) {
            return response()->json([
                "message" => "unauthorize",
                "data"    => null,
            ],401);
        }
        else {
            if (Crypt::decrypt($user->password) != $request->password) {
                return response()->json([
                    "message" => "unauthorize",
                    "data"    => null,
                ],401);
            }
            else {
                // create jwt token
                $payload  = [
                    "id"        => $user->id,
                    "privilege" => $user->level_id,
                    "expired"   => time()+86400,
                ];

                $jwtToken = $this->jwtService->encode($payload);

                // create response for api
                $response = response()->json([
                    "message" => "login success",
                    "data"    => [
                        "expired" => $payload['expired'],
                        'token'   => $jwtToken
                    ],
                ],200);

                // Set the cookie with the JWT token
                $cookieExpiration = $payload['expired'];
                $cookieName       = 'jwt_token';
                $cookieValue      = $jwtToken;
                setcookie($cookieName, $cookieValue, $cookieExpiration, "/");

                return $response;
            }
        }
    }

}
