<?php

namespace DescomMarket\Feeds\GoogleMerchant\Services\Products;

use DescomMarket\Feeds\GoogleMerchant\GoogleMerchantConnection;
use GuzzleHttp\Exception\ClientException;

class ProductsDeleteService extends GoogleMerchantConnection
{
    public function __invoke(string $productId): void
    {
        try {
            $this->client->delete("products/online:es:ES:{$productId}");
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                return;
            }

            throw $exception;
        }
    }
}
