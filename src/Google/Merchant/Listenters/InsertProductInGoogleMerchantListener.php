<?php

namespace DescomMarket\Feeds\Google\Merchant\Listenters;

use DescomMarket\Common\Events\Catalog\Products\ProductPublished;
use DescomMarket\Common\Repositories\Catalog\Products\ProductRepository;
use DescomMarket\Feeds\Google\Merchant\Services\Products\ProductsInsertService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class InsertProductInGoogleMerchantListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $delay = 60;
    public $tries = 10;

    public function __construct()
    {
        $this->delay = config('feeds-google.merchant.queue.delay');
        $this->tries = config('feeds-google.merchant.queue.tries');
    }

    public function handle(ProductPublished $event)
    {
        if (! config('feeds-google.merchant.enabled')) {
            logger()->debug('InsertProductInGoogleMerchantListener', [
                'product_id' => $event->productId,
                'result' => 'disabled',
            ]);

            return;
        }

        $service = new ProductsInsertService();

        $result = $service(ProductRepository::get($event->productId));

        logger()->debug('InsertProductInGoogleMerchantListener', [
            'product_id' => $event->productId,
            'result' => $result,
        ]);
    }

    public function viaConnection(): string
    {
        return config('feeds-google.merchant.queue.connection', 'sync');
    }

    public function viaQueue(): string
    {
        return config('feeds-google.merchant.queue.name', 'google_merchant');
    }

    public function failed(ProductPublished $event, Throwable $exception): void
    {
        report($exception);
    }
}
