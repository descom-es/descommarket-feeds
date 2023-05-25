<?php

namespace DescomMarket\Feeds\Tests\Stubs;

use DescomMarket\Common\Repositories\Catalog\Products\ProductRepositoryInterface;

class ProductRepositoryDriver implements ProductRepositoryInterface
{
    public function get(int $productId): ?array
    {
        return [
            'id' => $productId,
            'url' => 'https://example.com',
        ];
    }
}
