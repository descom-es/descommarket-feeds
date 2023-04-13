<?php

namespace DescomMarket\Feeds\Google\Merchant\Listenters;

use DescomMarket\Common\Events\Catalog\Products\ProductUpdated;
use DescomMarket\Common\Repositories\Catalog\Products\ProductRepository;
use DescomMarket\Feeds\Google\Merchant\Services\Products\ProductInsertService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class UpdateProductInGoogleMerchantListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $delay = 60;
    public $tries = 10;

    public function __construct()
    {
        $this->delay = config('feeds-google.merchant.queue.delay');
        $this->tries = config('feeds-google.merchant.queue.tries');
    }

    public function handle(ProductUpdated $event)
    {
        if (! $this->attributesChanged($event->attributesChanged)) {
            return;
        }

        $product = ProductRepository::get($event->productId);

        if (! $product) {
            return;
        }

        $command = new ProductInsertService();

        $command->run($product);

        logger()->debug('Product updated in Google Merchant', [
            'product_id' => $event->productId,
            'attributes_changed' => $event->attributesChanged,
        ]);
    }

    public function shouldQueue(ProductUpdated $event): bool
    {
        return $this->attributesChanged($event->attributesChanged);
    }

    private function attributesChanged(array $attributes): bool
    {
        $attributes = array_keys($attributes);

        $attributes = array_filter($attributes, function ($attribute) {
            return in_array($attribute, [
                'in_stock',
                'price',
                'offers',
                'extra',
            ]);
        });

        return count($attributes) > 0;
    }

    public function viaConnection(): string
    {
        return config('feeds-google.merchant.queue.connection', 'sync');
    }

    public function viaQueue(): string
    {
        return config('feeds-google.merchant.queue.name', 'google_merchant');
    }

    public function failed(ProductUpdated $event, Throwable $exception): void
    {
        report($exception);
    }
}
