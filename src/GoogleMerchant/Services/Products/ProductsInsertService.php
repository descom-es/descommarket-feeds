<?php

namespace DescomMarket\Feeds\GoogleMerchant\Services\Products;

use DescomMarket\Feeds\GoogleMerchant\GoogleMerchantConnection;
use DescomMarket\Feeds\GoogleMerchant\Services\Products\Helpers\ProductsServiceHelper;

class ProductsInsertService extends GoogleMerchantConnection
{
    public function __invoke(array $productData)
    {
            $response = $this->client->post('products', ['json' => ProductsServiceHelper::transformData($productData)]);

            return $response->getBody()->getContents();
    }
}
