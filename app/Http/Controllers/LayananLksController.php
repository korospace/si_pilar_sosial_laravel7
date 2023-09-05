<?php

namespace App\Http\Controllers;

use App\Http\Requests\LayananLksRequest;
use App\Models\LayananLks;
use App\Services\LayananLksService;
use Illuminate\Http\Request;

class LayananLksController extends Controller
{
    protected $layananLksService;

    public function __construct(LayananLksService $layananLksService)
    {
        $this->layananLksService = $layananLksService;
    }

    /**
     * View - Layanan LKS Main View
     *
     * - show main page of layanan LKS master
     * -------------------------------
     */
    public function layananLksMainView(Request $request)
    {

        $data = [
            'metaTitle' => 'Layanan LKS',
            'user'      => $request->user
        ];

        return view('pages/layanan_lks', $data);
    }

    /**
     * View - Layanan LKS Create View
     *
     * - show create page of layanan LKS
     * -------------------------------
     */
    public function layananLksCreateView(Request $request)
    {
        $data = [
            'metaTitle'  => 'Tambah Layanan LKS',
            'headTitle'  => 'Tambah Layanan LKS',
            'user'       => $request->user,
            'LayananLks' => null,
        ];

        return view('pages/layanan_lks-create-update', $data);
    }

    /**
     * View - Layanan LKS Update View
     *
     * - show update page of Layanan LKS
     * -------------------------------
     */
    public function layananLksUpdateView(Request $request, $id)
    {
        $data = [
            'metaTitle'  => 'Edit Layanan LKS',
            'headTitle'  => 'Edit Layanan LKS',
            'user'       => $request->user,
            'LayananLks' => LayananLks::where('id', $id)->first(),
        ];

        return view('pages/layanan_lks-create-update', $data);
    }

    /**
     * API - Get Layanan LKS - Data table
     * ---------------------------
     */
    public function getLayananLksDataTable(Request $request)
    {
        try {
            return $this->layananLksService->getLayananLksDataTable($request);
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
     * API - Create Layanan LKS
     * ---------------------------
     */
    public function createLayananLks(LayananLksRequest $layananLksRequest)
    {
        try {
            return $this->layananLksService->createLayananLks($layananLksRequest);
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
     * API - Update Layanan LKS
     * ---------------------------
     */
    public function updateLayananLks(LayananLksRequest $layananLksRequest)
    {
        try {
            return $this->layananLksService->updateLayananLks($layananLksRequest);
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
     * API - Delete Layanan LKS
     * ---------------------------
     */
    public function deleteLayananLks(LayananLksRequest $layananLksRequest, $id)
    {
        try {
            return $this->layananLksService->deleteLayananLks($layananLksRequest, $id);
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
