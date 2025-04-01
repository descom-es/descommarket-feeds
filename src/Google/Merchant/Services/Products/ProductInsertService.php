<?php

namespace DescomMarket\Feeds\Google\Merchant\Services\Products;

use DescomMarket\Feeds\Google\GoogleServiceBuilder;
use DescomMarket\Feeds\Google\Merchant\Services\Products\Transformer\ProductTransformer;

class ProductInsertService
{
    public function run(array $productData)
    {
        $merchantId = config('feeds-google.merchant.id');

        if (!$merchantId) {
            return;
        }

        $product = ProductTransformer::transform($productData);

        return GoogleServiceBuilder::googleMerchant()
            ->products
            ->insert($merchantId, $product);
    }
}
