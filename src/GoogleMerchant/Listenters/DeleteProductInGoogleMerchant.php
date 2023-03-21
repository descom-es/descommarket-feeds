<?php

namespace Descom\DescomMarket\Feeds\GoogleMerchant\Listenters;

class DeleteProductInGoogleMerchant
{
    public function handle(ProductUnpublished $event)
    {
        $productData = $event->product;

        ProductsDeleteService::delete($productData->sku);
    }
}
