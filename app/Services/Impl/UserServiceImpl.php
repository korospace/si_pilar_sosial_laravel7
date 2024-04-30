<?php

namespace App\Services\Impl;

use App\Exceptions\GeneralException;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UserServiceImpl implements UserService
{
    public function getUserDataTable(Request $request): JsonResponse
    {
        try {
            $no   = 1;
            $user = User::with(['user_level','site'])->where('id', '!=', $request->user->id)->orderBy('id', 'DESC')->get();

            return datatables()->of($user)
                ->addColumn('no', function ($row) use (&$no) {
                    return $no++;
                })
                ->addColumn('site', function ($row) use (&$no) {
                    return $row->site ? $row->site->name : '-';
                })
                ->addColumn('action', function ($row) {
                    $html = '
                        <a href="'.route('user.update', $row->id).'" class="btn_edit btn btn-sm bg-warning mb-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn_edit btn btn-sm bg-danger btn_delete mb-2" onclick="deleteUser(this,event,'.$row->id.')">
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

    public function createUser(UserRequest $request): JsonResponse
    {
        try {
            $newUser = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'level_id' => $request->level_id,
                'site_id'  => in_array($request->level_id, [1]) ? null : $request->site_id,
                'password' => Crypt::encrypt($request->password),
            ]);

            return response()->json(
                [
                    'message' => 'user baru berhasil dibuat',
                    'data'    => $newUser
                ],
                201
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function updateUser(UserRequest $request): JsonResponse
    {
        try {
            $arrUpate = [
                'name'     => $request->name,
                'email'    => $request->email,
                'level_id' => $request->level_id,
                'site_id'  => in_array($request->level_id, [1]) ? null : $request->site_id,
            ];

            if ($request->new_password) {
                $arrUpate['password'] = Crypt::encrypt($request->new_password);
            }

            User::where("id", $request->id)->update($arrUpate);

            return response()->json(
                [
                    'message' => 'user baru berhasil diedit',
                    'data'    => ''
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function deleteUser(UserRequest $request, string $id): JsonResponse
    {
        try {
            // cek site apakah ada
            $user = User::where('id', $id)->where('id', '!=', $request->user->id)->first();

            if ($user == null) {
                throw new GeneralException('data user tidak ditemukan', 404);
            }

            $user->delete();

            return response()->json(
                [
                    'message' => 'user berhasil dihapus',
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
