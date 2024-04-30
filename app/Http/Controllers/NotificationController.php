<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    protected $nService;

    public function __construct(NotificationService $nService)
    {
        $this->nService = $nService;
    }

    /**
     * API - forgot password
     */
    public function forgotPassword(NotificationRequest $request)
    {
        try {
            return $this->nService->forgotPassword($request);
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
