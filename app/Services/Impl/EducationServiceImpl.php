<?php

namespace App\Services\Impl;

use App\Exceptions\GeneralException;
use App\Http\Requests\EducationRequest;
use App\Models\Education;
use App\Services\EducationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EducationServiceImpl implements EducationService
{
    public function getEducationDataTable(Request $request): JsonResponse
    {
        try {
            $no = 1;
            $educations = Education::orderBy('id', 'DESC')->get();

            return datatables()->of($educations)
                ->addColumn('no', function ($row) use (&$no) {
                    return $no++;
                })
                ->addColumn('action', function ($row) {
                    $html = '
                        <a href="'.route('education.update', $row->id).'" class="btn_edit btn btn-sm bg-warning mb-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn_edit btn btn-sm bg-danger btn_delete mb-2" onclick="deleteEducation(this,event,'.$row->id.')">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';

                    return $html;
                })
                ->toJson();
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function createEducation(EducationRequest $request): JsonResponse
    {
        try {
            $newEducation = Education::create([
                'name' => $request->name
            ]);

            return response()->json(
                [
                    'message' => 'pendidikan baru berhasil dibuat',
                    'data'    => $newEducation
                ],
                201
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function updateEducation(EducationRequest $request): JsonResponse
    {
        try {
            Education::where('id', $request->id)->update([
                'name' => $request->name
            ]);

            return response()->json(
                [
                    'message' => 'pendidikan berhasil diedit',
                    'data'    => []
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function deleteEducation(EducationRequest $request, string $id): JsonResponse
    {
        try {
            // cek education apakah ada
            $education = Education::where('id', $id)->first();

            if ($education == null) {
                throw new GeneralException('data pendidikan tidak ditemukan', 404);
            }

            Education::where('id', $id)->delete();

            return response()->json(
                [
                    'message' => 'pendidikan berhasil dihapus',
                    'data'    => []
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }
}
