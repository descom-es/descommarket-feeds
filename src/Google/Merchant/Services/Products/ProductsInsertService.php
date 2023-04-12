<?php

namespace DescomMarket\Feeds\Google\Merchant\Services\Products;

use DescomMarket\Feeds\Google\GoogleConnection;
use DescomMarket\Feeds\Google\Merchant\Services\Products\Helpers\ProductsServiceHelper;

class ProductsInsertService extends GoogleConnection
{
    public function __invoke(array $productData)
    {
        $response = $this->client->post('products', ['json' => ProductsServiceHelper::transformData($productData)]);

        return $response->getBody()->getContents();
    }
}
