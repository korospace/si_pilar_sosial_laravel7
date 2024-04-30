<?php

namespace App\Services;

use App\Http\Requests\AkreditasiLksRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface AkreditasiLksService
{
    public function getAkreditasiLksDataTable(Request $request): JsonResponse;

    public function createAkreditasiLks(AkreditasiLksRequest $request): JsonResponse;

    public function updateAkreditasiLks(AkreditasiLksRequest $request): JsonResponse;

    public function deleteAkreditasiLks(AkreditasiLksRequest $request, string $id): JsonResponse;
}
