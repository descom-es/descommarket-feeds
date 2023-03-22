<?php

namespace DescomMarket\Feeds\GoogleMerchant\Services\Products;

use DescomMarket\Feeds\GoogleMerchant\GoogleMerchantConnection;

class ProductsListService extends GoogleMerchantConnection
{
    public function list()
    {
        $response = $this->client->get('products');

        return $response->getBody()->getContents();
    }
}
