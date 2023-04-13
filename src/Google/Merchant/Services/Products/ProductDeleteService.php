<?php

namespace DescomMarket\Feeds\Google\Merchant\Services\Products;

use DescomMarket\Feeds\Google\GoogleServiceBuilder;
use Google\Service\Exception;
use GuzzleHttp\Psr7\Response;

class ProductDeleteService
{
    public function run(int|string $productId): ?Response
    {
        $merchantId = config('feeds-google.merchant.id');

        if (! $merchantId) {
            return null;
        }

        $service = GoogleServiceBuilder::googleMerchant();

        try {
            return $service
                ->products
                ->delete($merchantId, "online:es:ES:{$productId}");
        } catch (Exception $exception) {
            if ($exception->getCode() === 404) {
                return null;
            }

            throw $exception;
        }
    }
}
