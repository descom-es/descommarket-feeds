<?php

namespace DescomMarket\Feeds\GoogleMerchant\Services\Products;

use Descom\B2b\Core\App\Feeds\GoogleMerchant\GoogleMerchantConnection;

class ProductsUpdateService extends GoogleMerchantConnection
{
    public function upsdate(int $productId, array $productData)
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
