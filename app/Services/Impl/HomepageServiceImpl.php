<?php

namespace App\Services\Impl;

use App\Exceptions\GeneralException;
use App\Models\Article;
use App\Models\Site;
use App\Services\HomepageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomepageServiceImpl implements HomepageService
{
    public function getPilarCounter(Request $request): JsonResponse
    {
        try {
            $newData  = [];
            $listSite = Site::all();

            foreach ($listSite as $row) {
                $newData['tksk'][] = [
                    'title' => $row->name,
                    'total' => DB::select("SELECT COUNT(*) AS total FROM tksk WHERE site_id = '".$row->id."'")[0]->total,
                ];
                $newData['psm'][] = [
                    'title' => $row->name,
                    'total' => DB::select("SELECT COUNT(*) AS total FROM psm WHERE site_id = '".$row->id."'")[0]->total,
                ];
                $newData['lks'][] = [
                    'title' => $row->name,
                    'total' => DB::select("SELECT COUNT(*) AS total FROM lks WHERE site_id = '".$row->id."'")[0]->total,
                ];
                $newData['karang_taruna'][] = [
                    'title' => $row->name,
                    'total' => DB::select("SELECT COUNT(*) AS total FROM karang_taruna WHERE site_id = '".$row->id."'")[0]->total,
                ];
            }

            return response()->json(
                [
                    'message' => 'berhasil',
                    'data'    => $newData
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function getArticleLatest(Request $request): JsonResponse
    {
        try {
            $data = Article::select('slug','title','excerpt','thumbnail', 'release_date', 'created_by')
                ->with([
                    'creator' => function ($q) {
                        $q->select('id','name');
                    }
                ])
                ->where('status','release')->where('release_date', '<', time())
                ->orderBy('release_date', 'DESC')
                ->limit(4)
                ->get();

            return response()->json(
                [
                    'message' => 'berhasil',
                    'data'    => $data
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function getArticlePagination(Request $request)
    {
        try {
            $articles = Article::query()
                        ->with([
                            'creator' => function ($q) {
                                $q->select('id','name');
                            }
                        ])
                        ->when($request->seach_term, function($q)use($request){
                            $q->where('title', 'like', '%'.$request->seach_term.'%')
                            ->orWhere('body', 'like', '%'.$request->seach_term.'%');
                        })
                        ->where('status','release')->where('release_date', '<', time())
                        ->paginate(4);

            return view('components/homepage-articles-child', compact('articles'))->render();
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function getArticleDetail(Request $request, $slug): JsonResponse
    {
        try {
            $data = Article::select('slug','title','body','thumbnail', 'release_date', 'created_by')
                ->with([
                    'creator' => function ($q) {
                        $q->select('id','name');
                    }
                ])
                ->where('slug',$slug)
                ->first();

            return response()->json(
                [
                    'message' => 'berhasil',
                    'data'    => $data
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function getArticleRecomendation(Request $request, $slug): JsonResponse
    {
        try {
            $data = Article::select('slug', 'title', 'body', 'thumbnail', 'release_date', 'created_by')
                ->with([
                    'creator' => function ($q) {
                        $q->select('id', 'name');
                    }
                ])
                ->where('slug', '!=', $slug)->where('status','release')->where('release_date', '<', time())
                ->inRandomOrder()
                ->limit(4)
                ->get();

            return response()->json(
                [
                    'message' => 'berhasil',
                    'data'    => $data
                ],
                200
            );
        } catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

}
