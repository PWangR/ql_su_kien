<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Services\SmtpConfigService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load SMTP config từ database
        SmtpConfigService::loadSmtpConfig();

        \Illuminate\Pagination\Paginator::defaultView('vendor.pagination.saas');

        // Force HTTPS in production and in environments behind proxies
        if (env('FORCE_HTTPS') === true || (env('APP_ENV') === 'production' && env('TRUST_PROXIES') === true)) {
            URL::forceScheme('https');
        }
    }
}
