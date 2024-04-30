<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;

interface LoginService
{
    public function loginWithCookie(LoginRequest $request): JsonResponse;
}
