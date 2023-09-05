<?php

namespace App\Services;

use App\Http\Requests\SiteRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface SiteService
{
    public function getSiteDataTable(Request $request): JsonResponse;

    public function getSiteAutocomplete(Request $request): JsonResponse;

    public function createSite(SiteRequest $request): JsonResponse;

    public function updateSite(SiteRequest $request): JsonResponse;

    public function deleteSite(SiteRequest $request, string $id): JsonResponse;
}
