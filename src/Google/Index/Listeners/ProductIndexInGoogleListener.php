<?php

namespace DescomMarket\Feeds\Google\Index\Listeners;

use DescomMarket\Common\Events\Catalog\Products\ProductPublished;
use DescomMarket\Common\Repositories\Catalog\Products\ProductRepository;
use DescomMarket\Feeds\Google\Index\Services\EnqueueUrlService;

class ProductIndexInGoogleListener
{
    public function handle(ProductPublished $event)
    {
        if (! config('feeds-google.index.enabled')) {
            return;
        }

        $product = ProductRepository::get($event->productId);

        if (! $product) {
            return;
        }

        EnqueueUrlService::publish($product['url'], 10);
    }
}
