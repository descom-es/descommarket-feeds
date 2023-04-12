<?php

namespace DescomMarket\Feeds;

use Illuminate\Support\ServiceProvider;

class DescomMarketFeedsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/feeds-google.php', 'feeds-google');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/feeds-google.php' => config_path('feeds-google.php'),
            ], 'config');
        }
    }
}
