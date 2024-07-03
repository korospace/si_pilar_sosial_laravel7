<?php

namespace App\Http\Controllers;

use App\Http\Requests\PsmRequest;
use App\Models\Education;
use App\Models\LogStatus;
use App\Models\Psm;
use App\Services\PsmService;
use Illuminate\Http\Request;

class PsmController extends Controller
{
    protected $psmService;

    public function __construct(PsmService $psmService)
    {
        $this->psmService = $psmService;
    }

    /**
     * View - PSM Main View
     *
     * - show main page
     * -------------------------------
     */
    public function psmMainView(Request $request)
    {

        $data = [
            'metaTitle' => 'PSM',
            'user'      => $request->user
        ];

        return view('pages/psm', $data);
    }

    /**
     * View - PSM Create View
     *
     * - show create page
     * -------------------------------
     */
    public function psmCreateView(Request $request)
    {
        $data = [
            'metaTitle' => 'Tambah PSM',
            'headTitle' => 'Tambah PSM',
            'user'      => $request->user,
            'psm'       => null,
            'educations'=> Education::all(),
        ];

        return view('pages/psm-create-update', $data);
    }

    /**
     * View - PSM Update View
     *
     * - show update page
     * -------------------------------
     */
    public function psmUpdateView(Request $request, $id)
    {
        setlocale(LC_TIME, 'id_ID');

        // get detail
        $dataPsm = Psm::where("id", $id)->first();
        // get status nonaktif
        $status_nonaktif = LogStatus::where("id_reference", $id)->where("table_reference", "psm")->where("status","nonaktif")->orderBy("id","desc")->first();
        // authorize site
        authorizeSite($request, $dataPsm->site_id);

        $data = [
            'metaTitle'         => $request->user->level_id == 1 ? 'Edit PSM' : 'Detil PSM',
            'headTitle'         => $request->user->level_id == 1 ? 'Edit PSM' : 'Detil PSM',
            'user'              => $request->user,
            'psm'               => $dataPsm,
            'status_nonaktif'   => $status_nonaktif,
            'educations'        => Education::all(),
        ];

        return view('pages/psm-create-update', $data);
    }

    /**
     * API - Get PSM - Data table
     * ---------------------------
     */
    public function getPsmDataTable(PsmRequest $request)
    {
        try {
            return $this->psmService->getPsmDataTable($request);
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
     * API - Get PSM - Info Status
     * ---------------------------
     */
    public function getPsmInfoStatus(PsmRequest $request)
    {
        try {
            return $this->psmService->getPsmInfoStatus($request);
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
    public function downloadExcel(PsmRequest $request)
    {
        try {
            return $this->psmService->downloadExcel($request);
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
     * API - Create PSM
     * ---------------------------
     */
    public function createPsm(PsmRequest $request)
    {
        try {
            return $this->psmService->createPsm($request);
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
     * API - Import PSM
     * ---------------------------
     */
    public function importPsm(PsmRequest $request)
    {
        try {
            return $this->psmService->importPsm($request);
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
     * API - Update PSM
     * ---------------------------
     */
    public function updatePsm(PsmRequest $request)
    {
        try {
            return $this->psmService->updatePsm($request);
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
     * API - Verif PSM
     * ---------------------------
     */
    public function verifPsm(PsmRequest $request)
    {
        try {
            return $this->psmService->verifPsm($request);
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
    public function updateStatus(PsmRequest $request)
    {
        try {
            return $this->psmService->updateStatus($request);
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
