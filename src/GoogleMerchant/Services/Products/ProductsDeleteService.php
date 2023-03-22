<?php

namespace DescomMarket\Feeds\GoogleMerchant\Services\Products;

use DescomMarket\Feeds\GoogleMerchant\GoogleMerchantConnection;

class ProductsDeleteService extends GoogleMerchantConnection
{
    public function delete(string $sku)
    {
        $response = $this->client->delete('products/' . $sku);

        return $response->getBody()->getContents();
    }
}
