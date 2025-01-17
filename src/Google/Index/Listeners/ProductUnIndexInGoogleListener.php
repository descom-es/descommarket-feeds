<?php

namespace DescomMarket\Feeds\Google\Index\Listeners;

use DescomMarket\Common\Events\Catalog\Products\ProductUnpublished;
use DescomMarket\Feeds\Google\Index\Services\EnqueueUrlService;

class ProductUnIndexInGoogleListener
{
    public function handle(ProductUnpublished $event)
    {
        if (! config('feeds-google.index.enabled')) {
            return;
        }

        $url = $event->attributes['url'] ?? null;

        if (! $url) {
            return;
        }

        EnqueueUrlService::unpublish($url);
    }
}
