<?php

namespace DescomMarket\Feeds\Google\Merchant\Services\Products;

use DescomMarket\Feeds\Google\GoogleConnection;
use GuzzleHttp\Exception\ClientException;

class ProductsDeleteService extends GoogleConnection
{
    public function __invoke(int|string $productId): void
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
