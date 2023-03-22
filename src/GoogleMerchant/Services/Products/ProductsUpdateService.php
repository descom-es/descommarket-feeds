<?php

namespace DescomMarket\Feeds\GoogleMerchant\Services\Products;

use DescomMarket\Feeds\GoogleMerchant\GoogleMerchantConnection;
use DescomMarket\Feeds\GoogleMerchant\Services\Products\Helpers\ProductsServiceHelper;

class ProductsUpdateService extends GoogleMerchantConnection
{
    public function update(string $sku, array $productData)
    {
        $response = $this->client->put('products/' . $sku, ProductsServiceHelper::transformData($productData)); //TODO: Falta modificar para sólo enviar datos que se actualizan

        return $response->getBody()->getContents();
    }
}
