<?php

namespace App\Services;

use App\Http\Requests\EducationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface EducationService
{
    public function getEducationDataTable(Request $request): JsonResponse;

    public function createEducation(EducationRequest $request): JsonResponse;

    public function updateEducation(EducationRequest $request): JsonResponse;

    public function deleteEducation(EducationRequest $request, string $id): JsonResponse;
}
