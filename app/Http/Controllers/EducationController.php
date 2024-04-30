<?php

namespace App\Http\Controllers;

use App\Http\Requests\EducationRequest;
use App\Models\Education;
use App\Services\EducationService;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    protected $eduService;

    public function __construct(EducationService $eduService)
    {
        $this->eduService = $eduService;
    }

    /**
     * View - Education Main View
     *
     * - show main page of education master
     * -------------------------------
     */
    public function educationMainView(Request $request)
    {

        $data = [
            'metaTitle' => 'Pendidikan',
            'user'      => $request->user
        ];

        return view('pages/education', $data);
    }

    /**
     * View - Education Create View
     *
     * - show create page of education
     * -------------------------------
     */
    public function educationCreateView(Request $request)
    {
        $data = [
            'metaTitle' => 'Tambah Pendidikan',
            'headTitle' => 'Tambah Pendidikan',
            'user'      => $request->user,
            'education' => null,
        ];

        return view('pages/education-create-update', $data);
    }

    /**
     * View - Site Update View
     *
     * - show update page of site
     * -------------------------------
     */
    public function educationUpdateView(Request $request, $id)
    {
        $data = [
            'metaTitle' => 'Edit Pendidikan',
            'headTitle' => 'Edit Pendidikan',
            'user'      => $request->user,
            'education' => Education::where('id', $id)->first(),
        ];

        return view('pages/education-create-update', $data);
    }

    /**
     * API - Get Education - Data table
     * ---------------------------
     */
    public function getEducationDataTable(Request $request)
    {
        try {
            return $this->eduService->getEducationDataTable($request);
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
     * API - Create Education
     * ---------------------------
     */
    public function createEducation(EducationRequest $educationRequest)
    {
        try {
            return $this->eduService->createEducation($educationRequest);
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
     * API - Update Education
     * ---------------------------
     */
    public function updateEducation(EducationRequest $educationRequest)
    {
        try {
            return $this->eduService->updateEducation($educationRequest);
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
     * API - Delete Education
     * ---------------------------
     */
    public function deleteEducation(EducationRequest $educationRequest, $id)
    {
        try {
            return $this->eduService->deleteEducation($educationRequest, $id);
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
