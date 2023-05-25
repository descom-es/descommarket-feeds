<?php

namespace DescomMarket\Feeds\Providers;

use DescomMarket\Common\Events\Catalog\Products\ProductPublished;
use DescomMarket\Common\Events\Catalog\Products\ProductUnpublished;
use DescomMarket\Feeds\Google\Index\Listeners\ProductIndexInGoogleListener;
use DescomMarket\Feeds\Google\Index\Listeners\ProductUnIndexInGoogleListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ProductPublished::class => [
           ProductIndexInGoogleListener::class,
        ],
        ProductUnpublished::class => [
            ProductUnIndexInGoogleListener::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
