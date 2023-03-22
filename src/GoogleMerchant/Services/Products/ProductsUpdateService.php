<?php

namespace DescomMarket\Feeds\GoogleMerchant\Services\Products;

use DescomMarket\Feeds\GoogleMerchant\GoogleMerchantConnection;

class ProductsUpdateService extends GoogleMerchantConnection
{
    public function update(int $productId, array $productData)
    {
        $response = $this->client->put('products/' . $productId, $this->transformData($productData));

        return $response->getBody()->getContents();
    }

    public function transformData(array $productData): array
    {
        $data = [];

        return $data;
    }
}
