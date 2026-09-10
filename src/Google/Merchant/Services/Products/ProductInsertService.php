<?php

namespace DescomMarket\Feeds\Google\Merchant\Services\Products;

use DescomMarket\Feeds\Google\Merchant\MerchantClientBuilder;
use DescomMarket\Feeds\Google\Merchant\Services\Products\Transformer\ProductTransformer;
use Google\Shopping\Merchant\Products\V1\InsertProductInputRequest;
use Google\Shopping\Merchant\Products\V1\ProductInput;

class ProductInsertService
{
    public function run(array $productData): ?ProductInput
    {
        $merchantId = config('feeds-google.merchant.id');

        if (! $merchantId) {
            return null;
        }

        $request = new InsertProductInputRequest();

        $request->setParent(MerchantClientBuilder::account());
        $request->setProductInput(ProductTransformer::transform($productData));
        $request->setDataSource(MerchantClientBuilder::dataSource());

        return MerchantClientBuilder::productInputs()->insertProductInput($request);
    }
}
