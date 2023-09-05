<?php

namespace App\Http\Controllers;

use App\Http\Requests\TkskRequest;
use App\Models\Education;
use App\Models\Tksk;
use App\Services\TkskService;
use Illuminate\Http\Request;

class TkskController extends Controller
{
    protected $tkskService;

    public function __construct(TkskService $tkskService)
    {
        $this->tkskService = $tkskService;
    }

    /**
     * View - TKSK Main View
     *
     * - show main page of tksk
     * -------------------------------
     */
    public function tkskMainView(Request $request)
    {

        $data = [
            'metaTitle' => 'TKSK',
            'user'      => $request->user
        ];

        return view('pages/tksk', $data);
    }

    /**
     * View - TKSK Create View
     *
     * - show create page of tksk
     * -------------------------------
     */
    public function tkskCreateView(Request $request)
    {
        $data = [
            'metaTitle' => 'Tambah TKSK',
            'headTitle' => 'Tambah TKSK',
            'user'      => $request->user,
            'tksk'      => null,
            'educations'=> Education::all(),
        ];

        return view('pages/tksk-create-update', $data);
    }

    /**
     * View - TKSK Update View
     *
     * - show update page of tksk
     * -------------------------------
     */
    public function tkskUpdateView(Request $request, $id)
    {
        setlocale(LC_TIME, 'id_ID');

        $data = [
            'metaTitle' => $request->user->level_id == 1 ? 'Edit TKSK' : 'Detil TKSK',
            'headTitle' => $request->user->level_id == 1 ? 'Edit TKSK' : 'Detil TKSK',
            'user'      => $request->user,
            'tksk'      => Tksk::where("id", $id)->first(),
            'educations'=> Education::all(),
        ];

        return view('pages/tksk-create-update', $data);
    }

    /**
     * API - Get TKSK - Data table
     * ---------------------------
     */
    public function getTkskDataTable(TkskRequest $request)
    {
        try {
            return $this->tkskService->getTkskDataTable($request);
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
     * API - Get TKSK - Info Status
     * ---------------------------
     */
    public function getTkskInfoStatus(TkskRequest $request)
    {
        try {
            return $this->tkskService->getTkskInfoStatus($request);
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
     * API - Create TKSK
     * ---------------------------
     */
    public function createTksk(TkskRequest $request)
    {
        try {
            return $this->tkskService->createTksk($request);
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
     * API - Import TKSK
     * ---------------------------
     */
    public function importTksk(TkskRequest $request)
    {
        try {
            return $this->tkskService->importTksk($request);
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
     * API - Verif TKSK
     * ---------------------------
     */
    public function verifTksk(TkskRequest $request)
    {
        try {
            return $this->tkskService->verifTksk($request);
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
     * API - Update TKSK
     * ---------------------------
     */
    public function updateTksk(TkskRequest $request)
    {
        try {
            return $this->tkskService->updateTksk($request);
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
