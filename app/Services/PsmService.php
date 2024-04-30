<?php

namespace App\Services;

use App\Http\Requests\PsmRequest;
use Illuminate\Http\JsonResponse;

interface PsmService
{
    public function getPsmDataTable(PsmRequest $request): JsonResponse;

    public function getPsmInfoStatus(PsmRequest $request): JsonResponse;

    public function createPsm(PsmRequest $request): JsonResponse;

    public function importPsm(PsmRequest $request): JsonResponse;

    public function verifPsm(PsmRequest $request): JsonResponse;

    public function updatePsm(PsmRequest $request): JsonResponse;
}
