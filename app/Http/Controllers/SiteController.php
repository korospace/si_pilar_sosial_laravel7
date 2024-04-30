<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteRequest;
use App\Models\Site;
use App\Services\SiteService;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    protected $siteService;

    public function __construct(SiteService $siteService)
    {
        $this->siteService = $siteService;
    }

    /**
     * View - Site Main View
     *
     * - show main page of site master
     * -------------------------------
     */
    public function siteMainView(Request $request)
    {

        $data = [
            'metaTitle' => 'Wilayah',
            'user'      => $request->user
        ];

        return view('pages/site', $data);
    }

    /**
     * View - Site Create View
     *
     * - show create page of site
     * -------------------------------
     */
    public function siteCreateView(Request $request)
    {
        $data = [
            'metaTitle' => 'Tambah Wilayah',
            'headTitle' => 'Tambah Wilayah',
            'user'      => $request->user,
            'site'      => null,
        ];

        return view('pages/site-create-update', $data);
    }

    /**
     * View - Site Update View
     *
     * - show update page of site
     * -------------------------------
     */
    public function siteUpdateView(Request $request, $id)
    {
        $data = [
            'metaTitle' => 'Edit Wilayah',
            'headTitle' => 'Edit Wilayah',
            'user'      => $request->user,
            'site'      => Site::where('id', $id)->first(),
        ];

        return view('pages/site-create-update', $data);
    }

    /**
     * API - Get Site - Data table
     * ---------------------------
     */
    public function getSiteDataTable(Request $request)
    {
        try {
            return $this->siteService->getSiteDataTable($request);
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

    /**
     * API - Get Site - Autocomplete
     * ---------------------------
     */
    public function getSiteAutocomplete(Request $request)
    {
        try {
            return $this->siteService->getSiteAutocomplete($request);
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

    /**
     * API - Create Site
     * ---------------------------
     */
    public function createSite(SiteRequest $siteRequest)
    {
        try {
            return $this->siteService->createSite($siteRequest);
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

    /**
     * API - Update Site
     * ---------------------------
     */
    public function updateSite(SiteRequest $siteRequest)
    {
        try {
            return $this->siteService->updateSite($siteRequest);
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

    /**
     * API - Delete Site
     * ---------------------------
     */
    public function deleteSite(SiteRequest $siteRequest, $id)
    {
        try {
            return $this->siteService->deleteSite($siteRequest, $id);
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

