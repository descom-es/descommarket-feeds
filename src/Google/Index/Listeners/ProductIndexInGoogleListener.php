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
        $noIndex = $this->getNoIndex($product);

        if (! $product || $noIndex) {
            return;
        }

        EnqueueUrlService::publish($product['url'], 10);
    }

    public function getNoIndex($product): bool
    {
        $robots = $product['meta']['robots'] ?? false;

        if (!$robots) {
            return false;
        }

        if ($robots && str_contains($robots, 'noindex')) {
            return true;
        }

        return false;
    }
}
