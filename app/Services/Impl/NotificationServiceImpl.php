<?php

namespace App\Services\Impl;

use App\Http\Requests\NotificationRequest;
use App\Models\User;
use App\Services\NotificationService;
use App\Notifications\ForgorPasswordEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Notification;

class NotificationServiceImpl implements NotificationService
{
    function forgotPassword(NotificationRequest $request): JsonResponse
    {
        try {
            $user = User::where('email', $request->email)->first();
            $user->password = Crypt::encrypt(uniqid());

            Notification::send($user, new ForgorPasswordEmail($user, Crypt::decrypt($user->password)));

            $user->save();

            return response()->json(
                [
                    'message' => "password baru telah dikirim",
                    'data'    => [],
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }
}
