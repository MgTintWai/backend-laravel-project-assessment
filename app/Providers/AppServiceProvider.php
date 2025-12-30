<?php

namespace App\Providers;

use App\Contracts\BaseInterface;
use App\Contracts\ProjectInterface;
use App\Models\Project;
use App\Observers\ProjectObserver;
use App\Repositories\BaseRepository;
use App\Repositories\ProjectRepository;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BaseInterface::class, BaseRepository::class);
        $this->app->bind(ProjectInterface::class, ProjectRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }

        // Observer forget cache
        Project::observe(ProjectObserver::class);
    }
}
