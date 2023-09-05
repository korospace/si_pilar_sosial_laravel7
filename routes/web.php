<?php

use App\Http\Controllers\AkreditasiLksController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\KarangTarunaController;
use App\Http\Controllers\LayananLksController;
use App\Http\Controllers\LksController;
use App\Http\Controllers\PsmController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\TkskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('', [HomepageController::class, 'homepageView'])->name('homepage.main');
Route::get('berita', [HomepageController::class, 'homepageArticlesView'])->name('homepage.articles');
Route::get('berita/{slug}', [HomepageController::class, 'homepageArticlesDetailView'])->name('homepage.articles.detail');

Route::group(['middleware' => ['PageGuard']], function () {
    /**
     * Login
     *
     * - show login.blade.php
     */
    Route::get('login', [LoginController::class, 'loginView'])->name('login');

    /**
     * Dashboard
     */
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('', [DashboardController::class, 'dashboardView'])->name('dashboard.main');
        Route::get('profile', [DashboardController::class, 'dashboardProfileView'])->name('dashboard.profile');
    });

    /**
     * Super Admin Page
     */
    Route::group(['middleware' => ['PageForSuperadmin']], function () {
        /**
         * Dashboard - Article / news
         */
        // 1- show crud-article.blade.php
        Route::get('article/main', [ArticleController::class, 'crudArticleMainView'])->name('crudarticle.main');
        // 2- show crud-article-create-update.blade.php
        Route::get('article/create', [ArticleController::class, 'articleCreateView'])->name('crudarticle.create');
        // 3- show crud-article-create-update.blade.php
        Route::get('article/update/{id}', [ArticleController::class, 'articleUpdateView'])->name('crudarticle.update');

        /**
         * Dashboard - Site Master
         */
        // 1- show site.blade.php
        Route::get('site/main', [SiteController::class, 'siteMainView'])->name('site.main');
        // 2- show site-create-update.blade.php
        Route::get('site/create', [SiteController::class, 'siteCreateView'])->name('site.create');
        // 3- show site-create-update.blade.php
        Route::get('site/update/{id}', [SiteController::class, 'siteUpdateView'])->name('site.update');

        /**
         * Dashboard - User Master
         *
         * - show user.blade.php
         */
        // 1- show user.blade.php
        Route::get('user/main', [UserController::class, 'userMainView'])->name('user.main');
        // 2- show user-create-update.blade.php
        Route::get('user/create', [UserController::class, 'userCreateView'])->name('user.create');
        // 3- show user-create-update.blade.php
        Route::get('user/update/{id}', [UserController::class, 'userUpdateView'])->name('user.update');

        /**
         * Dashboard - Education Master
         *
         * - show education.blade.php
         */
        // 1- show education.blade.php
        Route::get('education/main', [EducationController::class, 'educationMainView'])->name('education.main');
        // 2- show education-create-update.blade.php
        Route::get('education/create', [EducationController::class, 'educationCreateView'])->name('education.create');
        // 3- show education-create-update.blade.php
        Route::get('education/update/{id}', [EducationController::class, 'educationUpdateView'])->name('education.update');

        /**
         * Dashboard - Bank Master
         *
         * - show bank.blade.php
         */
        // 1- show bank.blade.php
        Route::get('bank/main', [BankController::class, 'bankMainView'])->name('bank.main');
        // 2- show bank-create-update.blade.php
        Route::get('bank/create', [BankController::class, 'bankCreateView'])->name('bank.create');
        // 3- show bank-create-update.blade.php
        Route::get('bank/update/{id}', [BankController::class, 'bankUpdateView'])->name('bank.update');

        /**
         * Dashboard - Layanan LKS Master
         *
         * - show layanan_lks.blade.php
         */
        // 1- show layanan_lks.blade.php
        Route::get('layanan_lks/main', [LayananLksController::class, 'layananLksMainView'])->name('layanan_lks.main');
        // 2- show layanan_lks-create-update.blade.php
        Route::get('layanan_lks/create', [LayananLksController::class, 'layananLksCreateView'])->name('layanan_lks.create');
        // 3- show layanan_lks-create-update.blade.php
        Route::get('layanan_lks/update/{id}', [LayananLksController::class, 'layananLksUpdateView'])->name('layanan_lks.update');

        /**
         * Dashboard - Akreditasi LKS Master
         *
         * - show akreditasi_lks.blade.php
         */
        // 1- show akreditasi_lks.blade.php
        Route::get('akreditasi_lks/main', [AkreditasiLksController::class, 'akreditasiLksMainView'])->name('akreditasi_lks.main');
        // 2- show akreditasi_lks-create-update.blade.php
        Route::get('akreditasi_lks/create', [AkreditasiLksController::class, 'akreditasiLksCreateView'])->name('akreditasi_lks.create');
        // 3- show akreditasi_lks-create-update.blade.php
        Route::get('akreditasi_lks/update/{id}', [AkreditasiLksController::class, 'akreditasiLksUpdateView'])->name('akreditasi_lks.update');
    });

    /**
     * TKSK
     */
    Route::group(['prefix' => 'tksk'], function () {
        Route::get('main', [TkskController::class, 'tkskMainView'])->name('tksk.main');
        Route::get('create', [TkskController::class, 'tkskCreateView'])->name('tksk.create');
        Route::get('{id}', [TkskController::class, 'tkskUpdateView'])->name('tksk.update');
    });

    /**
     * LKS
     */
    Route::group(['prefix' => 'lks'], function () {
        Route::get('main', [LksController::class, 'lksMainView'])->name('lks.main');
        Route::get('create', [LksController::class, 'lksCreateView'])->name('lks.create');
        Route::get('{id}', [LksController::class, 'lksUpdateView'])->name('lks.update');
    });

    /**
     * KARANG TARUNA
     */
    Route::group(['prefix' => 'karang_taruna'], function () {
        Route::get('main', [KarangTarunaController::class, 'karangTarunaMainView'])->name('karang_taruna.main');
        Route::get('create', [KarangTarunaController::class, 'karangTarunaCreateView'])->name('karang_taruna.create');
        Route::get('{id}', [KarangTarunaController::class, 'karangTarunaUpdateView'])->name('karang_taruna.update');
    });

    /**
     * PSM
     */
    Route::group(['prefix' => 'psm'], function () {
        Route::get('main', [PsmController::class, 'psmMainView'])->name('psm.main');
        Route::get('create', [PsmController::class, 'psmCreateView'])->name('psm.create');
        Route::get('{id}', [PsmController::class, 'psmUpdateView'])->name('psm.update');
    });

    /**
     * Logout
     *
     * - clear cookie jwt_token and redirect login
     */
    Route::get('logout', function () {
        Cookie::queue(Cookie::forget('jwt_token'));
        return redirect()->route('login');
    });
});

