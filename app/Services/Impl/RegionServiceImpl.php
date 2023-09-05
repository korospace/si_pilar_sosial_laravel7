<?php

namespace App\Services\Impl;

use App\Http\Requests\RegionRequest;
use App\Models\Region;
use App\Services\RegionService;
use Illuminate\Http\JsonResponse;

class RegionServiceImpl implements RegionService
{
    public function getRegionAutocomplete(RegionRequest $request): JsonResponse
    {
        try {
            $regions = Region::where("name","like","%".$request->query('name')."%");

            if ($request->type) {
                $regions = $regions->where('type', $request->type);
            }
            if ($request->region_id) {
                $splitSiteRegionId = explode(".", $request->region_id);

                if (isset($splitSiteRegionId[0])) {
                    $regions = $regions->where('prov_id', $splitSiteRegionId[0]);
                }
                if (isset($splitSiteRegionId[1])) {
                    $regions = $regions->where('kab_id', $splitSiteRegionId[1]);
                }
                if (isset($splitSiteRegionId[2])) {
                    $regions = $regions->where('kec_id', $splitSiteRegionId[2]);
                }
                if (isset($splitSiteRegionId[3])) {
                    $regions = $regions->where('kel_id', $splitSiteRegionId[3]);
                }
            }

            $regions = $regions->orderBy('name', 'ASC')->limit(20)->get();

            // mapping
            foreach ($regions as $region) {
                $region->text = $region->name;
            }

            return response()->json(
                [
                    'message' => 'berhasil',
                    'data'    => $regions
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }
}
