<?php

namespace App\Services;

use App\Http\Requests\LayananLksRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface LayananLksService
{
    public function getLayananLksDataTable(Request $request): JsonResponse;

    public function createLayananLks(LayananLksRequest $request): JsonResponse;

    public function updateLayananLks(LayananLksRequest $request): JsonResponse;

    public function deleteLayananLks(LayananLksRequest $request, string $id): JsonResponse;
}
