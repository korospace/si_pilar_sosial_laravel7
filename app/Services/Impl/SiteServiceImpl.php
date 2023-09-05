<?php

namespace App\Services\Impl;

use App\Exceptions\GeneralException;
use App\Http\Requests\SiteRequest;
use App\Models\Site;
use App\Models\User;
use App\Services\SiteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteServiceImpl implements SiteService
{
    public function getSiteDataTable(Request $request): JsonResponse
    {
        try {
            $no    = 1;
            $sites = Site::orderBy('id', 'DESC')->get();

            return datatables()->of($sites)
                ->addColumn('no', function ($row) use (&$no) {
                    return $no++;
                })
                ->addColumn('action', function ($row) {
                    $html = '
                        <a href="'.route('site.update', $row->id).'" class="btn_edit btn btn-sm bg-warning mb-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn_edit btn btn-sm bg-danger btn_delete mb-2" onclick="deleteSite(this,event,'.$row->id.')">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';

                    return $html;
                })
                ->toJson();
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getSiteAutocomplete(Request $request): JsonResponse
    {
        try {
            $sites = Site::where("name","like","%".$request->query('name')."%")->orderBy('name', 'ASC')->get();

            // mapping
            foreach ($sites as $site) {
                $site->text = $site->name;
            }

            return response()->json(
                [
                    'message' => 'berhasil',
                    'data'    => $sites
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createSite(SiteRequest $request): JsonResponse
    {
        try {
            $newSite = Site::create([
                'region_id' => $request->region_id,
                'name'      => $request->name,
            ]);

            return response()->json(
                [
                    'message' => 'site baru berhasil dibuat',
                    'data'    => $newSite
                ],
                201
            );
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateSite(SiteRequest $request): JsonResponse
    {
        try {
            Site::where('id', $request->id)->update([
                'name'      => $request->name
            ]);

            return response()->json(
                [
                    'message' => 'site berhasil diedit',
                    'data'    => []
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteSite(SiteRequest $request, string $id): JsonResponse
    {
        try {
            // cek site apakah ada
            $site = Site::where('id', $id)->first();

            if ($site == null) {
                throw new GeneralException('data site tidak ditemukan', 404);
            }

            // cek user dengan site $id
            $user = User::where('site_id', $id)->first();

            if ($user) {
                throw new GeneralException('terdapat user dengan site "'.$site->name.'" ', 409);
            }

            Site::where('id', $id)->delete();

            return response()->json(
                [
                    'message' => 'site berhasil dihapus',
                    'data'    => []
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }
}
