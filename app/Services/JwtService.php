<?php

namespace App\Services;

interface JwtService
{
    public function encode(array $payload): string;

    public function decode(string $token): array;
}