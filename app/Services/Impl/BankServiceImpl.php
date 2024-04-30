<?php

namespace App\Services\Impl;

use App\Exceptions\GeneralException;
use App\Http\Requests\BankRequest;
use App\Models\Bank;
use App\Services\BankService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankServiceImpl implements BankService
{
    public function getBankDataTable(Request $request): JsonResponse
    {
        try {
            $no = 1;
            $banks = Bank::orderBy('id', 'DESC')->get();

            return datatables()->of($banks)
                ->addColumn('no', function ($row) use (&$no) {
                    return $no++;
                })
                ->addColumn('action', function ($row) {
                    $html = '
                        <a href="'.route('bank.update', $row->id).'" class="btn_edit btn btn-sm bg-warning mb-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn_edit btn btn-sm bg-danger btn_delete mb-2" onclick="deleteBank(this,event,'.$row->id.')">
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

    public function getBankAutocomplete(Request $request): JsonResponse
    {
        try {
            $banks = Bank::where("name","like","%".$request->query('name')."%")->orderBy('name', 'ASC')->get();

            // mapping
            foreach ($banks as $bank) {
                $bank->text = $bank->name;
            }

            return response()->json(
                [
                    'message' => 'berhasil',
                    'data'    => $banks
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function createBank(BankRequest $request): JsonResponse
    {
        try {
            $newBank = Bank::create([
                'name' => $request->name,
                'code' => $request->code,
            ]);

            return response()->json(
                [
                    'message' => 'bank baru berhasil dibuat',
                    'data'    => $newBank
                ],
                201
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function updateBank(BankRequest $request): JsonResponse
    {
        try {
            Bank::where('id', $request->id)->update([
                'name' => $request->name,
                'code' => $request->code,
            ]);

            return response()->json(
                [
                    'message' => 'bank berhasil diedit',
                    'data'    => []
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function deleteBank(BankRequest $request, string $id): JsonResponse
    {
        try {
            // cek bank apakah ada
            $bank = Bank::where('id', $id)->first();

            if ($bank == null) {
                throw new GeneralException('data bank tidak ditemukan', 404);
            }

            Bank::where('id', $id)->delete();

            return response()->json(
                [
                    'message' => 'bank berhasil dihapus',
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
