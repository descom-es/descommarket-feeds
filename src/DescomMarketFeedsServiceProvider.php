<?php

namespace DescomMarket\Feeds;

use DescomMarket\Feeds\Console\IndexUrlCommand;
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

            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            $this->commands([
                IndexUrlCommand::class,
            ]);
        }
    }
}
