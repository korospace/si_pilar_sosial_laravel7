<?php

namespace App\Services;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface UserService
{
    public function getUserDataTable(Request $request): JsonResponse;

    public function createUser(UserRequest $request): JsonResponse;

    public function updateUser(UserRequest $request): JsonResponse;

    public function deleteUser(UserRequest $request, string $id): JsonResponse;
}
