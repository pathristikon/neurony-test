<?php

namespace App\Providers;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class ElasticSearchProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind('Elasticsearch\ClientBuilder', function ($app) {
            return ClientBuilder::create()
                ->setHosts([env('ELASTICSEARCH_HOST' . ':' . 'ELASTICSEARCH_PORT')])
                ->build();
        });
    }
}