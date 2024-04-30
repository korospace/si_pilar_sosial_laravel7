<?php

namespace App\Services;

use App\Http\Requests\NotificationRequest;
use Illuminate\Http\JsonResponse;

interface NotificationService
{
    public function forgotPassword(NotificationRequest $request): JsonResponse;
}
