<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\Maintenance;
use App\Models\Transaction;
use App\Models\User;
use App\Policies\AssetPolicy;
use App\Policies\MaintenancePolicy;
use App\Policies\TransactionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::policy(Asset::class, AssetPolicy::class);
        Gate::policy(Transaction::class, TransactionPolicy::class);
        Gate::policy(Maintenance::class, MaintenancePolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::define('view-reports', [\App\Policies\ReportPolicy::class, 'view']);
        Gate::define('export-reports', [\App\Policies\ReportPolicy::class, 'export']);
    }
}
