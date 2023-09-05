<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegionRequest;
use App\Services\RegionService;

class RegionController extends Controller
{
    protected $regionService;

    public function __construct(RegionService $regionService)
    {
        $this->regionService = $regionService;
    }

    /**
     * API - Get Region - Autocomplete
     * ---------------------------
     */
    public function getRegionAutocomplete(RegionRequest $request)
    {
        try {
            return $this->regionService->getRegionAutocomplete($request);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => [],
                ],
                is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }
}
