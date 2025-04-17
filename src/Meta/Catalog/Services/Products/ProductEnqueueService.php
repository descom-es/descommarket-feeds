<?php

namespace DescomMarket\Feeds\Meta\Catalog\Services\Products;

use DescomMarket\Feeds\Meta\Catalog\Models\MetaCatalogQueueModel;

class ProductEnqueueService
{
    private static function isEnabled(): bool
    {
        return (bool)config('feeds-meta.catalog.enabled');
    }

    public static function create(int $productId, int $priority = 50)
    {
        if (!self::isEnabled()) {
            return;
        }

        $metaCatalogQueueModel = MetaCatalogQueueModel::where('product_id', $productId)->first();

        if (!$metaCatalogQueueModel || $metaCatalogQueueModel->status === 'dispatched') {
            MetaCatalogQueueModel::create([
                'product_id' => $productId,
                'action' => 'create',
                'status' => 'pending',
                'priority' => $priority,
            ]);

            return;
        }
    }

    public static function update(array $productData, int $priority = 50)
    {
        if (!self::isEnabled()) {
            return;
        }
    }

    public static function delete(array $productData, int $priority = 50)
    {
        if (!self::isEnabled()) {
            return;
        }
    }
}
