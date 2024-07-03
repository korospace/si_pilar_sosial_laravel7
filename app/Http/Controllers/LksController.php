<?php

namespace App\Http\Controllers;

use App\Http\Requests\LksRequest;
use App\Models\AkreditasiLks;
use App\Models\LayananLks;
use App\Models\Lks;
use App\Models\LogStatus;
use App\Services\LksService;
use Illuminate\Http\Request;

class LksController extends Controller
{
    protected $lksService;

    public function __construct(LksService $lksService)
    {
        $this->lksService = $lksService;
    }

    /**
     * View - LKS Main View
     *
     * - show main page of lks
     * -------------------------------
     */
    public function lksMainView(Request $request)
    {

        $data = [
            'metaTitle' => 'LKS',
            'user'      => $request->user
        ];

        return view('pages/lks', $data);
    }

    /**
     * View - LKS Create View
     *
     * - show create page of lks
     * -------------------------------
     */
    public function lksCreateView(Request $request)
    {
        $data = [
            'metaTitle'     => 'Tambah LKS',
            'headTitle'     => 'Tambah LKS',
            'user'          => $request->user,
            'lks'           => null,
            'LayananLks'    => LayananLks::all(),
            'AkreditasiLks' => AkreditasiLks::all(),
        ];

        return view('pages/lks-create-update', $data);
    }

    /**
     * View - LKS Update View
     *
     * - show update page of lks
     * -------------------------------
     */
    public function lksUpdateView(Request $request, $id)
    {
        setlocale(LC_TIME, 'id_ID');

        // get detail
        $dataLks = Lks::where("id", $id)->first();
        // get status nonaktif
        $status_nonaktif = LogStatus::where("id_reference", $id)->where("table_reference", "lks")->where("status","nonaktif")->orderBy("id","desc")->first();
        // authorize site
        authorizeSite($request, $dataLks->site_id);

        $data = [
            'metaTitle'         => $request->user->level_id == 1 ? 'Edit LKS' : 'Detil LKS',
            'headTitle'         => $request->user->level_id == 1 ? 'Edit LKS' : 'Detil LKS',
            'user'              => $request->user,
            'lks'               => $dataLks,
            'status_nonaktif'   => $status_nonaktif,
            'LayananLks'        => LayananLks::all(),
            'AkreditasiLks'     => AkreditasiLks::all(),
        ];

        return view('pages/lks-create-update', $data);
    }

    /**
     * API - Get LKS - Data table
     * ---------------------------
     */
    public function getLksDataTable(LksRequest $request)
    {
        try {
            return $this->lksService->getLksDataTable($request);
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
     * API - Get LKS - Info Status
     * ---------------------------
     */
    public function getLksInfoStatus(LksRequest $request)
    {
        try {
            return $this->lksService->getLksInfoStatus($request);
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
     * API - Download Excel
     * ---------------------------
     */
    public function downloadExcel(LksRequest $request)
    {
        try {
            return $this->lksService->downloadExcel($request);
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
     * API - Create LKS
     * ---------------------------
     */
    public function createLks(LksRequest $request)
    {
        try {
            return $this->lksService->createLks($request);
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
     * API - Import LKS
     * ---------------------------
     */
    public function importLks(LksRequest $request)
    {
        try {
            return $this->lksService->importLks($request);
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
     * API - Update LKS
     * ---------------------------
     */
    public function updateLks(LksRequest $request)
    {
        try {
            return $this->lksService->updateLks($request);
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
     * API - Verif LKS
     * ---------------------------
     */
    public function verifLks(LksRequest $request)
    {
        try {
            return $this->lksService->verifLks($request);
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
     * API - Update Status
     * ---------------------------
     */
    public function updateStatus(LksRequest $request)
    {
        try {
            return $this->lksService->updateStatus($request);
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
