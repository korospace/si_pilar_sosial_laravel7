<?php

namespace App\Services;

use App\Http\Requests\LksRequest;
use Illuminate\Http\JsonResponse;

interface LksService
{
    public function getLksDataTable(LksRequest $request): JsonResponse;

    public function getLksInfoStatus(LksRequest $request): JsonResponse;

    public function createLks(LksRequest $request): JsonResponse;

    public function importLks(LksRequest $request): JsonResponse;

    public function updateLks(LksRequest $request): JsonResponse;

    public function verifLks(LksRequest $request): JsonResponse;

    public function updateStatus(LksRequest $request): JsonResponse;
}
