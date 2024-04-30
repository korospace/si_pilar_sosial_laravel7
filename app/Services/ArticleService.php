<?php

namespace App\Services;

use App\Http\Requests\ArticleRequest;
use Illuminate\Http\JsonResponse;

interface ArticleService
{
    public function getArticleDatatable(ArticleRequest $request): JsonResponse;

    public function createArticle(ArticleRequest $request): JsonResponse;

    public function updateArticle(ArticleRequest $request): JsonResponse;

    public function deleteArticle(ArticleRequest $request, string $id): JsonResponse;
}
