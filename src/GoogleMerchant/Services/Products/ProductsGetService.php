<?php

namespace DescomMarket\Feeds\GoogleMerchant\Services\Products;

use DescomMarket\Feeds\GoogleMerchant\GoogleMerchantConnection;

class ProductsGetService extends GoogleMerchantConnection
{
    public function get(string $sku)
    {
        $response = $this->client->get('products/' . $sku);

        return $response->getBody()->getContents();
    }
}
