<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Contracts\SearchableContract',
            'App\Repositories\ElasticsearchPostSearchRepository'
        );

        $this->app->bind(
            'App\Contracts\ElasticPostSearch',
            'App\Services\ElasticSearchPostService'
        );
    }
}
