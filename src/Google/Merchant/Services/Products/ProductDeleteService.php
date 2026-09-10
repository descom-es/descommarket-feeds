<?php

namespace DescomMarket\Feeds\Google\Merchant\Services\Products;

use DescomMarket\Feeds\Google\Merchant\MerchantClientBuilder;
use Google\ApiCore\ApiException;
use Google\ApiCore\ApiStatus;
use Google\Shopping\Merchant\Products\V1\DeleteProductInputRequest;

class ProductDeleteService
{
    public function run(int|string $productId): void
    {
        $merchantId = config('feeds-google.merchant.id');

        if (! $merchantId) {
            return;
        }

        $request = DeleteProductInputRequest::build(
            MerchantClientBuilder::productInputName($productId)
        );

        $request->setDataSource(MerchantClientBuilder::dataSource());

        try {
            MerchantClientBuilder::productInputs()->deleteProductInput($request);
        } catch (ApiException $exception) {
            // El producto ya no estaba en Merchant. El cliente nuevo devuelve
            // el estado gRPC, no el 404 de la Content API.
            if ($exception->getStatus() === ApiStatus::NOT_FOUND) {
                return;
            }

            throw $exception;
        }
    }
}
