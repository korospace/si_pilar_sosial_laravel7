<?php

namespace App\Services;

use App\Http\Requests\BankRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface BankService
{
    public function getBankDataTable(Request $request): JsonResponse;

    public function getBankAutocomplete(Request $request): JsonResponse;

    public function createBank(BankRequest $request): JsonResponse;

    public function updateBank(BankRequest $request): JsonResponse;

    public function deleteBank(BankRequest $request, string $id): JsonResponse;
}
