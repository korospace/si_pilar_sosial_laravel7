<?php

namespace App\Services;

use App\Http\Requests\DashboardRequest;
use Illuminate\Http\JsonResponse;

interface DashboardService
{
    public function editProfile(DashboardRequest $request): JsonResponse;
}
