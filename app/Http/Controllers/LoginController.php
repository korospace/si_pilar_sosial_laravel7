<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\LoginService;

class LoginController extends Controller
{
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    /**
     * Login View
     * ---------------------------
     */
    public function loginView()
    {
        $data = [
            'metaTitle' => 'Login Dashboard'
        ];

        return view('pages/login', $data);
    }

    /**
     * API - Login With Cookie
     * ---------------------------
     */
    public function loginWithCookie(LoginRequest $request)
    {
        return $this->loginService->loginWithCookie($request);
    }
}
