<?php

namespace DescomMarket\Feeds;

use Illuminate\Support\ServiceProvider;

class DescomMarketFeedsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/google-merchant.php', 'google-merchant');
    }
}
