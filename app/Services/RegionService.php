<?php

namespace App\Services;

use App\Http\Requests\RegionRequest;
use Illuminate\Http\JsonResponse;

interface RegionService
{
    public function getRegionAutocomplete(RegionRequest $request): JsonResponse;
}
