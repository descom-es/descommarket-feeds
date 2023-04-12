<?php

namespace DescomMarket\Feeds\GoogleMerchant\Listenters;

use DescomMarket\Common\Events\Catalog\Products\ProductPublished;
use DescomMarket\Common\Repositories\Catalog\Products\ProductRepository;
use DescomMarket\Feeds\GoogleMerchant\Services\Products\ProductsInsertService;
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
        $this->delay = config('google-merchant.queue.delay');
        $this->tries = config('google-merchant.queue.tries');
    }

    public function handle(ProductPublished $event)
    {
        if (! config('google-merchant.enabled')) {
            return;
        }

        $service = new ProductsInsertService();

        $result = $service(ProductRepository::get($event->productId));

        logger()->debug('InsertProductInGoogleMerchantListener', [
            'product_id' => $event->productId,
            'result' => $result,
        ])
    }

    public function viaConnection(): string
    {
        return config('google-merchant.queue.connection', 'sync');
    }

    public function viaQueue(): string
    {
        return config('google-merchant.queue.name', 'google_merchant');
    }

    public function failed(ProductPublished $event, Throwable $exception): void
    {
        report($exception);
    }
}
