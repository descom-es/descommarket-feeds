<?php

namespace Descom\DescomMarket\Feeds\GoogleMerchant\Services\Products;

use Descom\B2b\Core\App\Feeds\GoogleMerchant\GoogleMerchantConnection;

class ProductsGetService extends GoogleMerchantConnection
{
    public function get(int $productId)
    {
        $response = $this->client->get('products/' . $productId);

        return $response->getBody()->getContents();
    }
}
