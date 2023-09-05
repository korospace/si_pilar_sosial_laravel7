<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Services\JwtService;
use App\Services\Impl\JwtServiceImpl;
use App\Services\LoginService;
use App\Services\Impl\LoginServiceImpl;
use App\Services\SiteService;
use App\Services\Impl\SiteServiceImpl;
use App\Services\UserService;
use App\Services\Impl\UserServiceImpl;
use App\Services\EducationService;
use App\Services\Impl\EducationServiceImpl;
use App\Services\BankService;
use App\Services\Impl\BankServiceImpl;
use App\Services\RegionService;
use App\Services\Impl\RegionServiceImpl;
use App\Services\TkskService;
use App\Services\Impl\TkskServiceImpl;
use App\Services\LayananLksService;
use App\Services\Impl\LayananLksServiceImpl;
use App\Services\AkreditasiLksService;
use App\Services\Impl\AkreditasiLksServiceImpl;
use App\Services\LksService;
use App\Services\Impl\LksServiceImpl;
use App\Services\KarangTarunaService;
use App\Services\Impl\KarangTarunaServiceImpl;
use App\Services\PsmService;
use App\Services\Impl\PsmServiceImpl;
use App\Services\NotificationService;
use App\Services\Impl\NotificationServiceImpl;
use App\Services\DashboardService;
use App\Services\Impl\DashboardServiceImpl;
use App\Services\HomepageService;
use App\Services\Impl\HomepageServiceImpl;
use App\Services\ArticleService;
use App\Services\Impl\ArticleServiceImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // jwt
        $this->app->singleton(JwtService::class,JwtServiceImpl::class);
        // Home Page
        $this->app->singleton(HomepageService::class,HomepageServiceImpl::class);
        // login
        $this->app->singleton(LoginService::class, LoginServiceImpl::class);
        // Notification
        $this->app->singleton(NotificationService::class, NotificationServiceImpl::class);
        // Dashboard
        $this->app->singleton(DashboardService::class, DashboardServiceImpl::class);
        // region master
        $this->app->singleton(RegionService::class, RegionServiceImpl::class);
        // site master
        $this->app->singleton(SiteService::class, SiteServiceImpl::class);
        // user master
        $this->app->singleton(UserService::class, UserServiceImpl::class);
        // education master
        $this->app->singleton(EducationService::class, EducationServiceImpl::class);
        // bank master
        $this->app->singleton(BankService::class, BankServiceImpl::class);
        // layanan LKS master
        $this->app->singleton(LayananLksService::class, LayananLksServiceImpl::class);
        // akreditasi LKS master
        $this->app->singleton(AkreditasiLksService::class, AkreditasiLksServiceImpl::class);
        // TKSK
        $this->app->singleton(TkskService::class, TkskServiceImpl::class);
        // LKS
        $this->app->singleton(LksService::class, LksServiceImpl::class);
        // KARANG TARUNA
        $this->app->singleton(KarangTarunaService::class, KarangTarunaServiceImpl::class);
        // PSM
        $this->app->singleton(PsmService::class, PsmServiceImpl::class);
        // Article
        $this->app->singleton(ArticleService::class, ArticleServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
