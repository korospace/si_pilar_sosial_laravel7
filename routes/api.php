<?php

use App\Http\Controllers\AkreditasiLksController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\KarangTarunaController;
use App\Http\Controllers\LayananLksController;
use App\Http\Controllers\LksController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PsmController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\TkskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    /**
     *  Pilar Counter
     *
     * - url : /api/v1/pilar_counter
     */
    Route::get('pilar_counter', [HomepageController::class, 'getPilarCounter']);

    /**
     *  Articles - Latest
     *
     * - url : /api/v1/articles_latest
     */
    Route::get('articles_latest', [HomepageController::class, 'getArticleLatest']);

    /**
     *  Articles - Pagination
     *
     * - url : /api/v1/articles_pagination
     */
    Route::get('articles_pagination', [HomepageController::class, 'getArticlePagination']);

    /**
     *  Articles - Detail
     *
     * - url : /api/v1/articles_detail
     */
    Route::get('articles_detail/{slug}', [HomepageController::class, 'getArticleDetail']);

    /**
     *  Articles - Recomendation
     *
     * - url : /api/v1/articles_recomendation
     */
    Route::get('articles_recomendation/{slug}', [HomepageController::class, 'getArticleRecomendation']);

    /**
     * Login With Cookie
     *
     * - url      : /api/v1/login_cookie
     * - form-data: email, password
     */
    Route::post('login_cookie', [LoginController::class, 'loginWithCookie'])->middleware('throttle:10|60,1');

    /**
     * Forgot Password
     *
     * - url      : /api/v1/forgot_pass
     * - form-data: email
     */
    Route::post('forgot_pass', [NotificationController::class, 'forgotPassword']);

    /**
     *  Edit Profile
     *
     * - url      : /api/v1/edit_profile
     * - form-data: email, name, new_password
     */
    Route::put('edit_profile', [DashboardController::class, 'editProfile'])->middleware('ApiGuard');

    /**
     *  CRUD Article Endpoint
     *
     * - CRUD master data site
     */
    Route::group(['prefix' => 'crudarticle', 'middleware' => ['ApiGuard', 'ApiForSuperadmin']], function () {
        /**
         * Get With Datatable
         *
         * - url    : /api/v1/site/datatable
         * - params : none
         */
        Route::get('/datatable', [ArticleController::class, 'getArticleDataTable']);

        /**
         * Create New Article
         *
         * - url    : /api/v1/crudarticle/create
         * - form-data : thumbnail, title, status, release_date(opsional), body
         */
        Route::post('/create', [ArticleController::class, 'createArticle']);

        /**
         * Update Article
         *
         * - url    : /api/v1/crudarticle/update
         * - form-data : newthumbnail(opsional), title, status, release_date(opsional), body
         */
        Route::post('/update', [ArticleController::class, 'updateArticle']);

        /**
         * Delete Article
         *
         * - url    : /api/v1/crudarticle/delete/{id}
         * - query-param: id
         */
        Route::delete('/delete/{id}', [ArticleController::class, 'deleteArticle']);
    });

    /**
     * Site Endpoint
     *
     * - CRUD master data site
     */
    Route::group(['prefix' => 'site', 'middleware' => ['ApiGuard', 'ApiForSuperadmin']], function () {
        /**
         * Get With Datatable
         *
         * - url    : /api/v1/site/datatable
         * - params : none
         */
        Route::get('/datatable', [SiteController::class, 'getSiteDataTable']);

        /**
         * Create New Site
         *
         * - url    : /api/v1/site/create
         * - form-data : name
         */
        Route::post('/create', [SiteController::class, 'createSite']);

        /**
         * Update Site
         *
         * - url    : /api/v1/site/update
         * - form-data : id,name
         */
        Route::put('/update', [SiteController::class, 'updateSite']);

        /**
         * Delete Site
         *
         * - url    : /api/v1/site/delete/{id}
         * - query-param: id
         */
        Route::delete('/delete/{id}', [SiteController::class, 'deleteSite']);
    });

    /**
     * Education Endpoint
     *
     * - CRUD master data education
     */
    Route::group(['prefix' => 'education', 'middleware' => ['ApiGuard', 'ApiForSuperadmin']], function () {
        /**
         * Get With Datatable
         *
         * - url    : /api/v1/education/datatable
         * - params : none
         */
        Route::get('/datatable', [EducationController::class, 'getEducationDataTable']);

        /**
         * Create New Education
         *
         * - url    : /api/v1/education/create
         * - form-data : name
         */
        Route::post('/create', [EducationController::class, 'createEducation']);

        /**
         * Update Education
         *
         * - url    : /api/v1/education/update
         * - form-data : id,name
         */
        Route::put('/update', [EducationController::class, 'updateEducation']);

        /**
         * Delete Education
         *
         * - url    : /api/v1/education/delete/{id}
         * - query-param: id
         */
        Route::delete('/delete/{id}', [EducationController::class, 'deleteEducation']);
    });

    /**
     * Bank Endpoint
     *
     * - CRUD master data bank
     */
    Route::group(['prefix' => 'bank', 'middleware' => ['ApiGuard', 'ApiForSuperadmin']], function () {
        /**
         * Get With Datatable
         *
         * - url    : /api/v1/bank/datatable
         * - params : none
         */
        Route::get('/datatable', [BankController::class, 'getBankDataTable']);

        /**
         * Create New Bank
         *
         * - url    : /api/v1/bank/create
         * - form-data : name,code
         */
        Route::post('/create', [BankController::class, 'createBank']);

        /**
         * Update Bank
         *
         * - url    : /api/v1/bank/update
         * - form-data : id,name,code
         */
        Route::put('/update', [BankController::class, 'updateBank']);

        /**
         * Delete Bank
         *
         * - url    : /api/v1/bank/delete/{id}
         * - query-param: id
         */
        Route::delete('/delete/{id}', [BankController::class, 'deleteBank']);
    });

    /**
     * Layanan LKS Endpoint
     *
     * - CRUD master data layanan LKS
     */
    Route::group(['prefix' => 'layanan_lks', 'middleware' => ['ApiGuard', 'ApiForSuperadmin']], function () {
        /**
         * Get With Datatable
         *
         * - url    : /api/v1/layanan_lks/datatable
         * - params : none
         */
        Route::get('/datatable', [LayananLksController::class, 'getLayananLksDataTable']);

        /**
         * Create New Layanan LKS
         *
         * - url    : /api/v1/layanan_lks/create
         * - form-data : name
         */
        Route::post('/create', [LayananLksController::class, 'createLayananLks']);

        /**
         * Update Layanan LKS
         *
         * - url    : /api/v1/layanan_lks/update
         * - form-data : id,name
         */
        Route::put('/update', [LayananLksController::class, 'updateLayananLks']);

        /**
         * Delete Layanan LKS
         *
         * - url    : /api/v1/layanan_lks/delete/{id}
         * - query-param: id
         */
        Route::delete('/delete/{id}', [LayananLksController::class, 'deleteLayananLks']);
    });

    /**
     * Akreditasi LKS Endpoint
     *
     * - CRUD master data akreditasi LKS
     */
    Route::group(['prefix' => 'akreditasi_lks', 'middleware' => ['ApiGuard', 'ApiForSuperadmin']], function () {
        /**
         * Get With Datatable
         *
         * - url    : /api/v1/akreditasi_lks/datatable
         * - params : none
         */
        Route::get('/datatable', [AkreditasiLksController::class, 'getAkreditasiLksDataTable']);

        /**
         * Create New Akreditasi LKS
         *
         * - url    : /api/v1/akreditasi_lks/create
         * - form-data : name
         */
        Route::post('/create', [AkreditasiLksController::class, 'createAkreditasiLks']);

        /**
         * Update Akreditasi LKS
         *
         * - url    : /api/v1/akreditasi_lks/update
         * - form-data : id,name
         */
        Route::put('/update', [AkreditasiLksController::class, 'updateAkreditasiLks']);

        /**
         * Delete Akreditasi LKS
         *
         * - url    : /api/v1/akreditasi_lks/delete/{id}
         * - query-param: id
         */
        Route::delete('/delete/{id}', [AkreditasiLksController::class, 'deleteAkreditasiLks']);
    });

    /**
     * User Endpoint
     *
     * - CRUD master data user
     */
    Route::group(['prefix' => 'user', 'middleware' => ['ApiGuard', 'ApiForSuperadmin']], function () {
        /**
         * Get With Datatable
         *
         * - url    : /api/v1/user/datatable
         * - params : none
         */
        Route::get('/datatable', [UserController::class, 'getUserDataTable']);

        /**
         * Create New User
         *
         * - url    : /api/v1/user/create
         * - form-data : name, email, password, level_id, site_id
         */
        Route::post('/create', [UserController::class, 'createUser']);

        /**
         * Update Site
         *
         * - url    : /api/v1/site/update
         * - form-data : name, email, new_password, level_id, site_id
         */
        Route::put('/update', [UserController::class, 'updateUser']);

        /**
         * Delete User
         *
         * - url    : /api/v1/User/delete/{id}
         * - query-param: id
         */
        Route::delete('/delete/{id}', [UserController::class, 'deleteUser']);
    });

    /**
     * TKSK Endpoint
     *
     * - CRUD data tksk
     */
    Route::group(['prefix' => 'tksk', 'middleware' => ['ApiGuard']], function () {
        /**
         * Get With Datatable
         *
         * - url    : /api/v1/tksk/datatable
         * - params : none
         */
        Route::post('/datatable', [TkskController::class, 'getTkskDataTable']);

        /**
         * Get Info Status
         *
         * - url    : /api/v1/tksk/info_status
         * - params : none
         */
        Route::get('/info_status', [TkskController::class, 'getTkskInfoStatus']);

        /**
         * Download Excem
         *
         * - url    : /api/v1/tksk/download_excel
         * - params : none
         */
        Route::get('/download_excel', [TkskController::class, 'downloadExcel']);

        /**
         * Create New TKSK
         *
         * - url    : /api/v1/tksk/create
         * - form-data : so much
         */
        Route::post('/create', [TkskController::class, 'createTksk'])->middleware(['ApiForInputter']);

        /**
         * Import TKSK
         *
         * - url    : /api/v1/tksk/import
         * - form-data : site_id, file_tksk
         */
        Route::post('/import', [TkskController::class, 'importTksk'])->middleware(['ApiForInputter']);

        /**
         * Update TKSK
         *
         * - url    : /api/v1/tksk/update
         * - form-data : so much
         */
        Route::put('/update', [TkskController::class, 'updateTksk'])->middleware(['ApiForSuperadmin']);

        /**
         * Verif TKSK
         *
         * - url    : /api/v1/tksk/verif
         * - form-data : id, status
         */
        Route::put('/verif', [TkskController::class, 'verifTksk'])->middleware(['ApiForVerifier']);

        /**
         * Update Status
         *
         * - url    : /api/v1/tksk/update_status
         * - form-data : id, status, description
         */
        Route::put('/update_status', [TkskController::class, 'updateStatus'])->middleware(['ApiForVerifier']);
    });

    /**
     * LKS Endpoint
     *
     * - CRUD data lks
     */
    Route::group(['prefix' => 'lks', 'middleware' => ['ApiGuard']], function () {
        /**
         * Get With Datatable
         *
         * - url    : /api/v1/lks/datatable
         * - params : none
         */
        Route::post('/datatable', [LksController::class, 'getLksDataTable']);

        /**
         * Get Info Status
         *
         * - url    : /api/v1/lks/info_status
         * - params : none
         */
        Route::get('/info_status', [LksController::class, 'getLksInfoStatus']);

        /**
         * Download Excem
         *
         * - url    : /api/v1/lks/download_excel
         * - params : none
         */
        Route::get('/download_excel', [LksController::class, 'downloadExcel']);

        /**
         * Create New LKS
         *
         * - url    : /api/v1/lks/create
         * - form-data : so much
         */
        Route::post('/create', [LksController::class, 'createLks'])->middleware(['ApiForInputter']);

        /**
         * Import LKS
         *
         * - url    : /api/v1/lks/import
         * - form-data : site_id, file_lks
         */
        Route::post('/import', [LksController::class, 'importLks'])->middleware(['ApiForInputter']);

        /**
         * Update LKS
         *
         * - url    : /api/v1/lks/update
         * - form-data : so much
         */
        Route::put('/update', [LksController::class, 'updateLks'])->middleware(['ApiForSuperadmin']);

        /**
         * Verif LKS
         *
         * - url    : /api/v1/lks/verif
         * - form-data : id, status
         */
        Route::put('/verif', [LksController::class, 'verifLks'])->middleware(['ApiForVerifier']);

        /**
         * Update Status
         *
         * - url    : /api/v1/psm/update_status
         * - form-data : id, status, description
         */
        Route::put('/update_status', [LksController::class, 'updateStatus'])->middleware(['ApiForVerifier']);
    });

    /**
     * KARANG TARUNA Endpoint
     *
     * - CRUD data karang taruna
     */
    Route::group(['prefix' => 'karang_taruna', 'middleware' => ['ApiGuard']], function () {
        /**
         * Get With Datatable
         *
         * - url    : /api/v1/karang_taruna/datatable
         * - params : none
         */
        Route::post('/datatable', [KarangTarunaController::class, 'getKarangTarunaDataTable']);

        /**
         * Get Info Status
         *
         * - url    : /api/v1/karang_taruna/info_status
         * - params : none
         */
        Route::get('/info_status', [KarangTarunaController::class, 'getKarangTarunaInfoStatus']);

        /**
         * Download Excem
         *
         * - url    : /api/v1/karang_taruna/download_excel
         * - params : none
         */
        Route::get('/download_excel', [KarangTarunaController::class, 'downloadExcel']);

        /**
         * Create New
         *
         * - url    : /api/v1/karang_taruna/create
         * - form-data : so much
         */
        Route::post('/create', [KarangTarunaController::class, 'createKarangTaruna'])->middleware(['ApiForInputter']);

        /**
         * Import
         *
         * - url    : /api/v1/karang_taruna/import
         * - form-data : site_id, file_karang_taruna
         */
        Route::post('/import', [KarangTarunaController::class, 'importKarangTaruna'])->middleware(['ApiForInputter']);

        /**
         * Update
         *
         * - url    : /api/v1/karang_taruna/update
         * - form-data : so much
         */
        Route::put('/update', [KarangTarunaController::class, 'updateKarangTaruna'])->middleware(['ApiForSuperadmin']);

        /**
         * Verif
         *
         * - url    : /api/v1/karang_taruna/verif
         * - form-data : id, status
         */
        Route::put('/verif', [KarangTarunaController::class, 'verifKarangTaruna'])->middleware(['ApiForVerifier']);

        /**
         * Update Status
         *
         * - url    : /api/v1/karang_taruna/update_status
         * - form-data : id, status, description
         */
        Route::put('/update_status', [KarangTarunaController::class, 'updateStatus'])->middleware(['ApiForVerifier']);
    });

    /**
     * PSM Endpoint
     *
     * - CRUD data psm
     */
    Route::group(['prefix' => 'psm', 'middleware' => ['ApiGuard']], function () {
        /**
         * Get With Datatable
         *
         * - url    : /api/v1/psm/datatable
         * - params : none
         */
        Route::post('/datatable', [PsmController::class, 'getPsmDataTable']);

        /**
         * Get Info Status
         *
         * - url    : /api/v1/psm/info_status
         * - params : none
         */
        Route::get('/info_status', [PsmController::class, 'getPsmInfoStatus']);

        /**
         * Download Excem
         *
         * - url    : /api/v1/psm/download_excel
         * - params : none
         */
        Route::get('/download_excel', [PsmController::class, 'downloadExcel']);

        /**
         * Create New
         *
         * - url    : /api/v1/psm/create
         * - form-data : so much
         */
        Route::post('/create', [PsmController::class, 'createPsm'])->middleware(['ApiForInputter']);

        /**
         * Import
         *
         * - url    : /api/v1/psm/import
         * - form-data : site_id, file_karang_taruna
         */
        Route::post('/import', [PsmController::class, 'importPsm'])->middleware(['ApiForInputter']);

        /**
         * Update
         *
         * - url    : /api/v1/psm/update
         * - form-data : so much
         */
        Route::put('/update', [PsmController::class, 'updatePsm'])->middleware(['ApiForSuperadmin']);

        /**
         * Verif
         *
         * - url    : /api/v1/psm/verif
         * - form-data : id, status
         */
        Route::put('/verif', [PsmController::class, 'verifPsm'])->middleware(['ApiForVerifier']);

        /**
         * Update Status
         *
         * - url    : /api/v1/psm/update_status
         * - form-data : id, status, description
         */
        Route::put('/update_status', [PsmController::class, 'updateStatus'])->middleware(['ApiForVerifier']);
    });

    /**
     * Autocomplete Endpoint
     */
    Route::group(['prefix' => 'autocomplete', 'middleware' => ['ApiGuard']], function () {
        /**
         * Get For Region
         *
         * - url    : /api/v1/autocomplete/region
         * - params : name, type
         */
        Route::get('/region', [RegionController::class, 'getRegionAutocomplete']);

        /**
         * Get For Site
         *
         * - url    : /api/v1/autocomplete/site
         * - params : name
         */
        Route::get('/site', [SiteController::class, 'getSiteAutocomplete']);

        /**
         * Get For Bank
         *
         * - url    : /api/v1/autocomplete/bank
         * - params : name
         */
        Route::get('/bank', [BankController::class, 'getBankAutocomplete']);
    });

    /**
     *  Downloads
     * -------------------------------------------
     */
    // Excel Tmp
    Route::get('download_excel_tmp', function (Request $request) {
        $file_name      = $request->query('file_name');
        $full_file_name = env("EXCEL_FOLDER") . "/" . $file_name;

        if (file_exists($full_file_name)) {
            return response()->download($full_file_name)->deleteFileAfterSend(true);
        }
    });
});
