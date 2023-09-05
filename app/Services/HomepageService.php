<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface HomepageService
{
    public function getPilarCounter(Request $request): JsonResponse;

    public function getArticleLatest(Request $request): JsonResponse;

    public function getArticlePagination(Request $request);

    public function getArticleDetail(Request $request, $slug): JsonResponse;

    public function getArticleRecomendation(Request $request, $slug): JsonResponse;
}
