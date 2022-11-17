<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Services\MarketDataService\Client;
use Services\MarketDataService\PolygonIo\PolygonClient;

class MarketDataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            return match (config('market-data-service.default')) {
                'polygon_io' => new PolygonClient(),
            };
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
