<?php

namespace App\Http\Controllers;

use App\Http\Requests\AkreditasiLksRequest;
use App\Models\AkreditasiLks;
use App\Services\AkreditasiLksService;
use Illuminate\Http\Request;

class AkreditasiLksController extends Controller
{
    protected $akreditasiLksService;

    public function __construct(AkreditasiLksService $akreditasiLksService)
    {
        $this->akreditasiLksService = $akreditasiLksService;
    }

    /**
     * View - Akreditasi LKS Main View
     *
     * - show main page of akreditasi LKS master
     * -------------------------------
     */
    public function akreditasiLksMainView(Request $request)
    {

        $data = [
            'metaTitle' => 'Akreditasi LKS',
            'user'      => $request->user
        ];

        return view('pages/akreditasi_lks', $data);
    }

    /**
     * View - Akreditasi LKS Create View
     *
     * - show create page of akreditasi LKS
     * -------------------------------
     */
    public function akreditasiLksCreateView(Request $request)
    {
        $data = [
            'metaTitle'  => 'Tambah Akreditasi LKS',
            'headTitle'  => 'Tambah Akreditasi LKS',
            'user'       => $request->user,
            'AkreditasiLks' => null,
        ];

        return view('pages/akreditasi_lks-create-update', $data);
    }

    /**
     * View - Akreditasi LKS Update View
     *
     * - show update page of Akreditasi LKS
     * -------------------------------
     */
    public function akreditasiLksUpdateView(Request $request, $id)
    {
        $data = [
            'metaTitle'  => 'Edit Akreditasi LKS',
            'headTitle'  => 'Edit Akreditasi LKS',
            'user'       => $request->user,
            'AkreditasiLks' => AkreditasiLks::where('id', $id)->first(),
        ];

        return view('pages/akreditasi_lks-create-update', $data);
    }

    /**
     * API - Get Akreditasi LKS - Data table
     * ---------------------------
     */
    public function getAkreditasiLksDataTable(Request $request)
    {
        try {
            return $this->akreditasiLksService->getAkreditasiLksDataTable($request);
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
     * API - Create Akreditasi LKS
     * ---------------------------
     */
    public function createAkreditasiLks(AkreditasiLksRequest $request)
    {
        try {
            return $this->akreditasiLksService->createAkreditasiLks($request);
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
     * API - Update Akreditasi LKS
     * ---------------------------
     */
    public function updateAkreditasiLks(AkreditasiLksRequest $request)
    {
        try {
            return $this->akreditasiLksService->updateAkreditasiLks($request);
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
     * API - Delete Akreditasi LKS
     * ---------------------------
     */
    public function deleteAkreditasiLks(AkreditasiLksRequest $request, $id)
    {
        try {
            return $this->akreditasiLksService->deleteAkreditasiLks($request, $id);
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
