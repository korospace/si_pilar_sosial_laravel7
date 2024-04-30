<?php

namespace App\Services\Impl;

use App\Exceptions\GeneralException;
use App\Http\Requests\AkreditasiLksRequest;
use App\Models\AkreditasiLks;
use App\Services\AkreditasiLksService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AkreditasiLksServiceImpl implements AkreditasiLksService
{
    public function getAkreditasiLksDataTable(Request $request): JsonResponse
    {
        try {
            $no = 1;
            $rows = AkreditasiLks::orderBy('id', 'DESC')->get();

            return datatables()->of($rows)
                ->addColumn('no', function ($row) use (&$no) {
                    return $no++;
                })
                ->addColumn('action', function ($row) {
                    $html = '
                        <a href="'.route('akreditasi_lks.update', $row->id).'" class="btn_edit btn btn-sm bg-warning mb-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn_edit btn btn-sm bg-danger btn_delete mb-2" onclick="deleteAkreditasiLks(this,event,'.$row->id.')">
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

    public function createAkreditasiLks(AkreditasiLksRequest $request): JsonResponse
    {
        try {
            $newAkreditasi = AkreditasiLks::create([
                'name' => $request->name
            ]);

            return response()->json(
                [
                    'message' => 'akreditasi LKS baru berhasil dibuat',
                    'data'    => $newAkreditasi
                ],
                201
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function updateAkreditasiLks(AkreditasiLksRequest $request): JsonResponse
    {
        try {
            AkreditasiLks::where('id', $request->id)->update([
                'name' => $request->name
            ]);

            return response()->json(
                [
                    'message' => 'akreditasi LKS berhasil diedit',
                    'data'    => []
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function deleteAkreditasiLks(AkreditasiLksRequest $request, string $id): JsonResponse
    {
        try {
            // cek akreditasi LKS apakah ada
            $row = AkreditasiLks::where('id', $id)->first();

            if ($row == null) {
                throw new GeneralException('data akreditasi LKS tidak ditemukan', 404);
            }

            AkreditasiLks::where('id', $id)->delete();

            return response()->json(
                [
                    'message' => 'akreditasi LKS berhasil dihapus',
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
