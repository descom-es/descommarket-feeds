<?php

namespace Descom\DescomMarket\Feeds\GoogleMerchant\Listenters;

use Descom\DescomMarket\Feeds\GoogleMerchant\Services\Products\ProductsCreateService;

class SaveProductInGoogleMerchant
{
    public function handle(ProductPublished $event)
    {
        $productData = $event->product;

        $productGM = ProductsGetService::get($productData->sku);

        if ($productGM) {
            ProductsUpdateService::update($productData->sku, $productData);

            return;
        }

        ProductsCreateService::create($productData);
    }
}
