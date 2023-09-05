<?php

namespace App\Services\Impl;

use App\Exceptions\GeneralException;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Services\ArticleService;
use Carbon\Carbon;
use DOMDocument;
use HTMLPurifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticleServiceImpl implements ArticleService
{
    protected $filePathStoreAs = 'public/thumbnail-article';
    protected $storeFilePath   = 'app/public/thumbnail-article';

    public function getArticleDatatable(ArticleRequest $request): JsonResponse
    {
        try {
            $no   = 1;
            $rows = Article::query();

            $rows = $rows->select('id', 'thumbnail', 'status', 'title', 'excerpt', 'release_date', 'created_by')->orderBy('id', 'DESC')->with('creator')->get();

            return datatables()->of($rows)
                ->addColumn('no', function ($row) use (&$no) {
                    return $no++;
                })
                ->addColumn('thumbnail', function ($row) {
                    $html = "
                        <img src='".$row->public_thumbnail."' class='w-100 img-thumbnail'>
                    ";

                    return $html;
                })
                ->addColumn('status', function ($row) use ($request) {
                    $statusClass = "";

                    if ($row->status == 'draft') {
                        $statusClass = "btn-outline-warning";
                    }
                    else if ($row->status == 'release') {
                        $statusClass = "btn-outline-success";
                    }

                    $html = '
                        <span class="btn '.$statusClass.' btn-sm" style="width: max-content;">
                            '.$row->status.'
                        </span>
                    ';

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '
                        <a href="'.route('crudarticle.update', $row->id).'" class="btn_edit btn btn-sm bg-warning mb-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn_edit btn btn-sm bg-danger btn_delete mb-2" onclick="deleteArticle(this,event,'.$row->id.')">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';

                    return $html;
                })
                ->rawColumns(['thumbnail', 'status', 'action'])
                ->toJson();
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createArticle(ArticleRequest $request): JsonResponse
    {
        try {
            return DB::transaction(function () use ($request) {
                /* Create File Name */
                $file = $request->file('thumbnail');
                $fileName = 'thumbnail-' . uniqid() . '.' . $file->getClientOriginalExtension();

                /* Save File */
                if (is_dir(storage_path($this->storeFilePath)) === false) {
                    mkdir(storage_path($this->storeFilePath));
                }

                $file->storeAs($this->filePathStoreAs, $fileName);

                /* Save Data */
                $newData = Article::create([
                    'title'         => $request->title,
                    'slug'          => Str::slug($request->title),
                    'thumbnail'     => $fileName,
                    'status'        => $request->status,
                    'release_date'  => $request->status == 'release' ? strtotime($request->release_date) : null,
                    'excerpt'       => $this->createExcerpt($request->body, 200),
                    'body'          => $request->body,
                    'created_by'    => $request->user->id,
                ]);

                return response()->json(
                    [
                        'message' => 'berhasil menyimpan artikel',
                        'data'    => $newData
                    ],
                    200
                );
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateArticle(ArticleRequest $request): JsonResponse
    {
        try {
            return DB::transaction(function () use ($request) {
                /* Get Old Data */
                $oldData = Article::select('thumbnail')->where('id', $request->id)->first();

                /* Create File Name */
                $file     = "";
                $fileName = $oldData->thumbnail;

                if ($request->hasFile('newthumbnail')) {
                    $file     = $request->file('newthumbnail');
                    $fileName = 'thumbnail-' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Hapus file lama jika ada
                    if (Storage::exists($this->filePathStoreAs. '/' . $oldData->thumbnail)) {
                        Storage::delete($this->filePathStoreAs. '/' . $oldData->thumbnail);
                    }

                    $file->storeAs($this->filePathStoreAs, $fileName);
                }


                /* Update Data */
                Article::where("id", $request->id)->update([
                    'title'         => $request->title,
                    'slug'          => Str::slug($request->title),
                    'thumbnail'     => $fileName,
                    'status'        => $request->status,
                    'release_date'  => $request->status == 'release' ? strtotime($request->release_date) : null,
                    'excerpt'       => $this->createExcerpt($request->body, 200),
                    'body'          => $request->body,
                    'updated_by'    => $request->user->id,
                ]);

                return response()->json(
                    [
                        'message' => 'edit artikel berhasil',
                        'data'    => ''
                    ],
                    200
                );
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteArticle(ArticleRequest $request, string $id): JsonResponse
    {
        try {
            return DB::transaction(function () use ($id) {
                $data = Article::select('thumbnail')->where('id', $id)->first();

                if ($data == null) {
                    throw new GeneralException('data article tidak ditemukan', 404);
                }

                // Hapus file lama jika ada
                if (Storage::exists($this->filePathStoreAs. '/' . $data->thumbnail)) {
                    Storage::delete($this->filePathStoreAs. '/' . $data->thumbnail);
                }

                Article::where('id', $id)->delete();

                return response()->json(
                    [
                        'message' => 'article berhasil dihapus',
                        'data'    => []
                    ],
                    200
                );
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected function createExcerpt($content, $limit) {
        // Clean HTML and ensure safe content
        $purifier = new HTMLPurifier();
        $cleanedContent = $purifier->purify($content);

        // Create a DOMDocument for parsing
        $doc = new DOMDocument();
        $doc->loadHTML('<meta http-equiv="content-type" content="text/html; charset=utf-8">' . $cleanedContent);

        // Find the first image tag
        $imageTag = $doc->getElementsByTagName('img')->item(0);

        if ($imageTag) {
            // If there's an image in the first line, remove it and its parent
            $imageParent = $imageTag->parentNode;
            $imageParent->parentNode->removeChild($imageParent);
        }

        // Get the text content without HTML tags
        $textContent = strip_tags($doc->saveHTML());

        // Limit the excerpt by characters
        $excerpt = Str::limit($textContent, $limit);

        return $excerpt;
    }
}
