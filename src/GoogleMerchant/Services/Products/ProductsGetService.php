<?php

namespace DescomMarket\Feeds\GoogleMerchant\Services\Products;

use DescomMarket\Feeds\GoogleMerchant\GoogleMerchantConnection;

class ProductsGetService extends GoogleMerchantConnection
{
    public function get(int $productId)
    {
        $response = $this->client->get('products/' . $productId);

        return $response->getBody()->getContents();
    }
}
