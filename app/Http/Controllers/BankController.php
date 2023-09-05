<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankRequest;
use App\Models\Bank;
use App\Services\BankService;
use Illuminate\Http\Request;

class BankController extends Controller
{
    protected $bankService;

    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }

    /**
     * View - Bank Main View
     *
     * - show main page of bank master
     * -------------------------------
     */
    public function bankMainView(Request $request)
    {

        $data = [
            'metaTitle' => 'Bank',
            'user'      => $request->user
        ];

        return view('pages/bank', $data);
    }

    /**
     * View - Bank Create View
     *
     * - show create page of bank
     * -------------------------------
     */
    public function bankCreateView(Request $request)
    {
        $data = [
            'metaTitle' => 'Tambah Bank',
            'headTitle' => 'Tambah Bank',
            'user'      => $request->user,
            'bank'      => null,
        ];

        return view('pages/bank-create-update', $data);
    }

    /**
     * View - Bank Update View
     *
     * - show update page of bank
     * -------------------------------
     */
    public function bankUpdateView(Request $request, $id)
    {
        $data = [
            'metaTitle' => 'Edit Bank',
            'headTitle' => 'Edit Bank',
            'user'      => $request->user,
            'bank'      => Bank::where('id', $id)->first(),
        ];

        return view('pages/bank-create-update', $data);
    }

    /**
     * API - Get Bank - Data table
     * ---------------------------
     */
    public function getBankDataTable(Request $request)
    {
        try {
            return $this->bankService->getBankDataTable($request);
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
     * API - Get Region - Autocomplete
     * ---------------------------
     */
    public function getBankAutocomplete(Request $request)
    {
        try {
            return $this->bankService->getBankAutocomplete($request);
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
     * API - Create Bank
     * ---------------------------
     */
    public function createBank(BankRequest $bankRequest)
    {
        try {
            return $this->bankService->createBank($bankRequest);
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
     * API - Update Bank
     * ---------------------------
     */
    public function updateBank(BankRequest $bankRequest)
    {
        try {
            return $this->bankService->updateBank($bankRequest);
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
     * API - Delete Bank
     * ---------------------------
     */
    public function deleteBank(BankRequest $bankRequest, $id)
    {
        try {
            return $this->bankService->deleteBank($bankRequest, $id);
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
