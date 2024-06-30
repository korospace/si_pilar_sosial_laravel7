<?php

namespace App\Http\Controllers;

use App\Http\Requests\KarangTarunaRequest;
use App\Models\KarangTaruna;
use App\Models\LogStatus;
use App\Services\KarangTarunaService;
use Illuminate\Http\Request;

class KarangTarunaController extends Controller
{
    protected $ktService;

    public function __construct(KarangTarunaService $ktService)
    {
        $this->ktService = $ktService;
    }

    /**
     * View - Main View
     *
     * - show main page
     * -------------------------------
     */
    public function karangTarunaMainView(Request $request)
    {

        $data = [
            'metaTitle' => 'KARANG TARUNA',
            'user'      => $request->user
        ];

        return view('pages/karang_taruna', $data);
    }

    /**
     * View - Create View
     *
     * - show create page
     * -------------------------------
     */
    public function karangTarunaCreateView(Request $request)
    {
        $data = [
            'metaTitle'     => 'Tambah KARANG TARUNA',
            'headTitle'     => 'Tambah KARANG TARUNA',
            'user'          => $request->user,
            'karangTaruna'  => null,
        ];

        return view('pages/karang_taruna-create-update', $data);
    }

    /**
     * View - Update View
     *
     * - show update page
     * -------------------------------
     */
    public function karangTarunaUpdateView(Request $request, $id)
    {
        setlocale(LC_TIME, 'id_ID');
        
        // get detail
        $dataKt = KarangTaruna::where("id", $id)->first();
        // get status nonaktif
        $status_nonaktif = LogStatus::where("id_reference", $id)->where("table_reference", "karang_taruna")->where("status","nonaktif")->orderBy("id","desc")->first();
        // authorize site
        authorizeSite($request, $dataKt->site_id);

        $data = [
            'metaTitle'         => $request->user->level_id == 1 ? 'Edit KARANG TARUNA' : 'Detil KARANG TARUNA',
            'headTitle'         => $request->user->level_id == 1 ? 'Edit KARANG TARUNA' : 'Detil KARANG TARUNA',
            'user'              => $request->user,
            'karangTaruna'      => $dataKt,
            'status_nonaktif'   => $status_nonaktif,
        ];

        return view('pages/karang_taruna-create-update', $data );
    }

    /**
     * API - Get - Data table
     * ---------------------------
     */
    public function getKarangTarunaDataTable(KarangTarunaRequest $request)
    {
        try {
            return $this->ktService->getKarangTarunaDataTable($request);
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
     * API - Get - Info Status
     * ---------------------------
     */
    public function getKarangTarunaInfoStatus(KarangTarunaRequest $request)
    {
        try {
            return $this->ktService->getKarangTarunaInfoStatus($request);
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
     * API - Create
     * ---------------------------
     */
    public function createKarangTaruna(KarangTarunaRequest $request)
    {
        try {
            return $this->ktService->createKarangTaruna($request);
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
     * API - Import
     * ---------------------------
     */
    public function importKarangTaruna(KarangTarunaRequest $request)
    {
        try {
            return $this->ktService->importKarangTaruna($request);
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
     * API - Update
     * ---------------------------
     */
    public function updateKarangTaruna(KarangTarunaRequest $request)
    {
        try {
            return $this->ktService->updateKarangTaruna($request);
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
     * API - Verif
     * ---------------------------
     */
    public function verifKarangTaruna(KarangTarunaRequest $request)
    {
        try {
            return $this->ktService->verifKarangTaruna($request);
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
    public function updateStatus(KarangTarunaRequest $request)
    {
        try {
            return $this->ktService->updateStatus($request);
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
