<?php

namespace DescomMarket\Feeds;

use Illuminate\Support\ServiceProvider;

class DescomMarketFeedsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/google-merchant.php', 'google-merchant');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/google-merchant.php' => config_path('google-merchant.php'),
            ], 'config');
        }
    }
}
