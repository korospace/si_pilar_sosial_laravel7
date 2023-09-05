<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $aService;

    public function __construct(ArticleService $aService)
    {
        $this->aService = $aService;
    }

    /**
     * View - Crud Article Main View
     *
     * - show main page of crud article
     * -------------------------------
     */
    public function crudArticleMainView(Request $request)
    {

        $data = [
            'metaTitle' => 'List Artikel',
            'user'      => $request->user
        ];

        return view('pages/crud-article', $data);
    }

    /**
     * View - Article Create View
     *
     * - show create page of article
     * -------------------------------
     */
    public function articleCreateView(Request $request)
    {
        $data = [
            'metaTitle' => 'Tambah Berita',
            'headTitle' => 'Tambah Berita',
            'user'      => $request->user,
            'article'   => null,
        ];

        return view('pages/crud-article-create-update', $data);
    }

    /**
     * View - Article Update View
     *
     * - show update page of article
     * -------------------------------
     */
    public function articleUpdateView(Request $request, $id)
    {
        $data = [
            'metaTitle' => 'Edit Berita',
            'headTitle' => 'Edit Berita',
            'user'      => $request->user,
            'article'   => Article::where('id', $id)->first(),
        ];

        return view('pages/crud-article-create-update', $data);
    }

    /**
     * API - Get Article - Data table
     * ---------------------------
     */
    public function getArticleDataTable(ArticleRequest $request)
    {
        try {
            return $this->aService->getArticleDataTable($request);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => [],
                ],
                500
                // is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }

    /**
     * API - Create Article
     * ---------------------------
     */
    public function createArticle(ArticleRequest $request)
    {
        try {
            return $this->aService->createArticle($request);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => [],
                    'code'    => is_int($th->getCode())
                ],
                is_int($th->getCode()) ? 500/*$th->getCode()*/ : 500
            );
        }
    }

    /**
     * API - Update Article
     * ---------------------------
     */
    public function updateArticle(ArticleRequest $request)
    {
        try {
            return $this->aService->updateArticle($request);
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                    'data'    => [],
                ],
                500
                // is_int($th->getCode()) ? $th->getCode() : 500
            );
        }
    }

    /**
     * API - Delete Article
     * ---------------------------
     */
    public function deleteArticle(ArticleRequest $request, $id)
    {
        try {
            return $this->aService->deleteArticle($request, $id);
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
