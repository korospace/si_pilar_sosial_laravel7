<?php

namespace App\Services;

use App\Http\Requests\TkskRequest;
use Illuminate\Http\JsonResponse;

interface TkskService
{
    public function getTkskDataTable(TkskRequest $request): JsonResponse;

    public function getTkskInfoStatus(TkskRequest $request): JsonResponse;

    public function createTksk(TkskRequest $request): JsonResponse;

    public function importTksk(TkskRequest $request): JsonResponse;

    public function updateTksk(TkskRequest $request): JsonResponse;

    public function verifTksk(TkskRequest $request): JsonResponse;

    public function updateStatus(TkskRequest $request): JsonResponse;

}
