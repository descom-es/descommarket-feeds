<?php

namespace DescomMarket\Feeds\GoogleMerchant\Services\Products;

use DescomMarket\Feeds\GoogleMerchant\GoogleMerchantConnection;

class ProductsDeleteService extends GoogleMerchantConnection
{
    public function delete(int $productId)
    {
        $response = $this->client->delete('products/' . $productId);

        return $response->getBody()->getContents();
    }
}
