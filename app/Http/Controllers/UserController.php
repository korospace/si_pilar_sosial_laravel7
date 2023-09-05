<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Site;
use App\Models\User;
use App\Models\UserLevel;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * View - User Main View
     *
     * - show main page of user master
     * -------------------------------
     */
    public function userMainView(Request $request)
    {

        $data = [
            'metaTitle' => 'User',
            'user'      => $request->user
        ];

        return view('pages/user', $data);
    }

    /**
     * View - User Create View
     *
     * - show create page of user
     * -------------------------------
     */
    public function userCreateView(Request $request)
    {
        $data = [
            'metaTitle' => 'Tambah User',
            'headTitle' => 'Tambah User',
            'user'      => $request->user,
            'site'      => Site::all(),
            'userLevels'=> UserLevel::all(),
            'userEdit'  => null,
        ];

        return view('pages/user-create-update', $data);
    }

    /**
     * View - User Update View
     *
     * - show update page of user
     * -------------------------------
     */
    public function userUpdateView(Request $request, $id)
    {
        $data = [
            'metaTitle' => 'Edit User',
            'headTitle' => 'Edit User',
            'user'      => $request->user,
            'site'      => Site::all(),
            'userLevels'=> UserLevel::all(),
            'userEdit'  => User::with('site')->where('id', $id)->first(),
        ];

        return view('pages/user-create-update', $data);
    }

    /**
     * API - Get User - Data table
     * ---------------------------
     */
    public function getUserDataTable(Request $request)
    {
        try {
            return $this->userService->getUserDataTable($request);
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
     * API - Create User
     * ---------------------------
     */
    public function createUser(UserRequest $userRequest)
    {
        try {
            return $this->userService->createUser($userRequest);
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
     * API - Update User
     * ---------------------------
     */
    public function updateUser(UserRequest $userRequest)
    {
        try {
            return $this->userService->updateUser($userRequest);
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
     * API - Delete User
     * ---------------------------
     */
    public function deleteUser(UserRequest $userRequest, $id)
    {
        try {
            return $this->userService->deleteUser($userRequest, $id);
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
