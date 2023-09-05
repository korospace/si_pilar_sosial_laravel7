<?php

namespace App\Services;

use App\Http\Requests\KarangTarunaRequest;
use Illuminate\Http\JsonResponse;

interface KarangTarunaService
{
    public function getKarangTarunaDataTable(KarangTarunaRequest $request): JsonResponse;

    public function getKarangTarunaInfoStatus(KarangTarunaRequest $request): JsonResponse;

    public function createKarangTaruna(KarangTarunaRequest $request): JsonResponse;

    public function importKarangTaruna(KarangTarunaRequest $request): JsonResponse;

    public function verifKarangTaruna(KarangTarunaRequest $request): JsonResponse;

    public function updateKarangTaruna(KarangTarunaRequest $request): JsonResponse;
}
