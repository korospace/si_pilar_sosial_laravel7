<?php

namespace App\Services\Impl;

use App\Exceptions\GeneralException;
use App\Http\Requests\LayananLksRequest;
use App\Models\LayananLks;
use App\Services\LayananLksService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LayananLksServiceImpl implements LayananLksService
{
    public function getLayananLksDataTable(Request $request): JsonResponse
    {
        try {
            $no = 1;
            $rows = LayananLks::orderBy('id', 'DESC')->get();

            return datatables()->of($rows)
                ->addColumn('no', function ($row) use (&$no) {
                    return $no++;
                })
                ->addColumn('action', function ($row) {
                    $html = '
                        <a href="'.route('layanan_lks.update', $row->id).'" class="btn_edit btn btn-sm bg-warning mb-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn_edit btn btn-sm bg-danger btn_delete mb-2" onclick="deleteLayananLks(this,event,'.$row->id.')">
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

    public function createLayananLks(LayananLksRequest $request): JsonResponse
    {
        try {
            $newLayanan = LayananLks::create([
                'name' => $request->name
            ]);

            return response()->json(
                [
                    'message' => 'layanan LKS baru berhasil dibuat',
                    'data'    => $newLayanan
                ],
                201
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function updateLayananLks(LayananLksRequest $request): JsonResponse
    {
        try {
            LayananLks::where('id', $request->id)->update([
                'name' => $request->name
            ]);

            return response()->json(
                [
                    'message' => 'layanan LKS berhasil diedit',
                    'data'    => []
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function deleteLayananLks(LayananLksRequest $request, string $id): JsonResponse
    {
        try {
            // cek layanan LKS apakah ada
            $row = LayananLks::where('id', $id)->first();

            if ($row == null) {
                throw new GeneralException('data layanan LKS tidak ditemukan', 404);
            }

            LayananLks::where('id', $id)->delete();

            return response()->json(
                [
                    'message' => 'layanan LKS berhasil dihapus',
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
