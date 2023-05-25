<?php

namespace DescomMarket\Feeds\Google\Index\Listeners;

use DescomMarket\Common\Events\Catalog\Products\ProductUnpublished;
use DescomMarket\Common\Repositories\Catalog\Products\ProductRepository;
use DescomMarket\Feeds\Google\Index\Services\EnqueueUrlService;

class ProductUnIndexInGoogleListener
{
    public function handle(ProductUnpublished $event)
    {
        if (! config('feeds-google.index.enabled')) {
            return;
        }

        // TODO no lo encontrará quizas sea mejor idea conectar a la DB
        $product = ProductRepository::get($event->productId);

        if (! $product) {
            return;
        }

        EnqueueUrlService::unpublish($product['url']);
    }
}
