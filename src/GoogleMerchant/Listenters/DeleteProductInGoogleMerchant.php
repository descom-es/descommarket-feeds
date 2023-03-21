<?php

namespace Descom\DescomMarket\Feeds\GoogleMerchant\Listenters;

use Descom\DescomMarket\Feeds\GoogleMerchant\Services\Products\ProductsCreateService;

class DeleteProductInGoogleMerchant
{
    public function handle(ProductUnpublished $event)
    {
        $productData = $event->product;

        ProductsDeleteService::delete($productData->sku);
    }
}
