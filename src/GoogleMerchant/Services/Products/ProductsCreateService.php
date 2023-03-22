<?php

namespace DescomMarket\Feeds\GoogleMerchant\Services\Products;

use DescomMarket\Feeds\GoogleMerchant\GoogleMerchantConnection;
use DescomMarket\Feeds\GoogleMerchant\Services\Products\Helpers\ProductsServiceHelper;

class ProductsCreateService extends GoogleMerchantConnection
{
    public function create(array $productData)
    {
        $response = $this->client->post('products', ProductsServiceHelper::transformData($productData));

        return $response->getBody()->getContents();
    }
}
