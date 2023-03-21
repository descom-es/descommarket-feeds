<?php

namespace Descom\DescomMarket\Feeds\GoogleMerchant\Services\Products;

use Descom\B2b\Core\App\Feeds\GoogleMerchant\GoogleMerchantConnection;

class ProductsListService extends GoogleMerchantConnection
{
    public function list()
    {
        $response = $this->client->get('products');

        return $response->getBody()->getContents();
    }
}
