<?php

namespace Descom\DescomMarket\Feeds\GoogleMerchant\Services\Products;

use Descom\B2b\Core\App\Feeds\GoogleMerchant\GoogleMerchantConnection;

class ProductsDeleteService extends GoogleMerchantConnection
{
    public function delete(int $productId)
    {
        $response = $this->client->delete('products/' . $productId);

        return $response->getBody()->getContents();
    }
}
