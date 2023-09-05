<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\HomepageService;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    protected $hService;

    public function __construct(HomepageService $hService)
    {
        $this->hService = $hService;
    }

    public function homepageView()
    {
        $data = [
            'metaTitle' => 'SI PILAR SOSIAL',
        ];

        return view('pages/homepage', $data);
    }

    public function homepageArticlesView()
    {
        $data = [
            'metaTitle' => 'DAFTAR BERITA',
            'articles'  => Article::query()
                ->with([
                    'creator' => function ($q) {
                        $q->select('id','name');
                    }
                ])
                ->paginate(4)
        ];

        return view('pages/homepage-articles', $data);
    }

    public function homepageArticlesDetailView(Request $request, $slug)
    {
        $article = Article::select('title', 'slug', 'thumbnail')->where('slug', $slug)->first();

        if ($article == null) {
            abort(404);
        }
        else {
            $data       = [
                'metaTitle' => $article->title,
                'article'   => $article
            ];

            return view('pages/homepage-articles-detail', $data);
        }
    }

    /**
     * API - Get - Pilar Counter
     * ---------------------------
     */
    public function getPilarCounter(Request $request)
    {
        try {
            return $this->hService->getPilarCounter($request);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => $th->getCode(),
                ],
                is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }

    /**
     * API - Get - Articles Latest
     * ---------------------------
     */
    public function getArticleLatest(Request $request)
    {
        try {
            return $this->hService->getArticleLatest($request);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => $th->getCode(),
                ],
                is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }

    /**
     * API - Get - Articles Pagination
     * -------------------------------
     */
    public function getArticlePagination(Request $request)
    {
        try {
            return $this->hService->getArticlePagination($request);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => $th->getCode(),
                ],
                is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }

    /**
     * API - Get - Articles Detail
     * -------------------------------
     */
    public function getArticleDetail(Request $request, $slug)
    {
        try {
            return $this->hService->getArticleDetail($request, $slug);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => $th->getCode(),
                ],
                is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }

    /**
     * API - Get - Articles Recomendation
     * -------------------------------
     */
    public function getArticleRecomendation(Request $request, $slug)
    {
        try {
            return $this->hService->getArticleRecomendation($request, $slug);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => $th->getCode(),
                ],
                is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }
}
