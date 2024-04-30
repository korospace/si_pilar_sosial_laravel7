<?php

namespace App\Services\Impl;

use App\Services\JwtService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtServiceImpl implements JwtService
{
    public function encode(array $payload): string
    {
        return JWT::encode($payload, env('APP_KEY'), 'HS256');
    }

    public function decode(string $token): array
    {
        $decoded = JWT::decode($token, new Key(env('APP_KEY'), 'HS256'));
        return (array)$decoded;
    }
}
