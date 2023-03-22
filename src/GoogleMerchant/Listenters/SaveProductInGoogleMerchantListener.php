<?php

namespace DescomMarket\Feeds\GoogleMerchant\Listenters;

use DescomMarket\Common\Events\Catalog\Products\ProductPublished;
use DescomMarket\Common\Repositories\Catalog\Products\ProductRepository;
use DescomMarket\Feeds\GoogleMerchant\Services\Products\ProductsGetService;
use DescomMarket\Feeds\GoogleMerchant\Services\Products\ProductsCreateService;
use DescomMarket\Feeds\GoogleMerchant\Services\Products\ProductsUpdateService;

class SaveProductInGoogleMerchantListener
{
    public function handle(ProductPublished $event)
    {
        $productData = ProductRepository::get($event->productId);

        if (!isset($productData['sku'])) {
            logger()->error("Product without sku in SaveProductInGoogleMerchantListener. ProductId: $event->productId");
            return;
        }

        $productGetService = new ProductsGetService();
        $productGM = $productGetService->get($productData['sku']);

        if ($productGM) {
            $productsUpdateService = new ProductsUpdateService();
            $productsUpdateService->update($productData['sku'], $productData);
            return;
        }

        $productsCreateService = new ProductsCreateService();
        $productsCreateService->create($productData);
    }
}
