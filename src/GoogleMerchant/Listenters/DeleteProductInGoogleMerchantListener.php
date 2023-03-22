<?php

namespace DescomMarket\Feeds\GoogleMerchant\Listenters;

use DescomMarket\Common\Events\Catalog\Products\ProductUnpublished;
use DescomMarket\Common\Repositories\Catalog\Products\ProductRepository;
use DescomMarket\Feeds\GoogleMerchant\Services\Products\ProductsDeleteService;

class DeleteProductInGoogleMerchantListener
{
    public function handle(ProductUnpublished $event)
    {
        $productData = ProductRepository::get($event->productId);

        if (! isset($productData['sku'])) {
            logger()->error("Product without sku in DeleteProductInGoogleMerchantListener. ProductId: $event->productId");

            return;
        }

        $productsDeleteService = new ProductsDeleteService();
        $productsDeleteService->delete($productData['sku']);
    }
}
