<?php

namespace DescomMarket\Feeds\GoogleMerchant\Listenters;

use DescomMarket\Common\Events\Catalog\Products\ProductUnpublished;
use DescomMarket\Feeds\GoogleMerchant\Services\Products\ProductsDeleteService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class DeleteProductInGoogleMerchantListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $delay = 60;
    public $tries = 10;

    public function __construct()
    {
        $this->delay = config('google-merchant.queue.delay');
        $this->tries = config('google-merchant.queue.tries');
    }

    public function handle(ProductUnpublished $event)
    {
        if (! config('google-merchant.enabled')) {
            return;
        }

        $service = new ProductsDeleteService();

        $service($event->productId);
    }

    public function viaConnection(): string
    {
        return config('google-merchant.queue.connection');
    }

    public function viaQueue(): string
    {
        return config('google-merchant.queue.name');
    }

    public function failed(ProductUnpublished $event, Throwable $exception): void
    {
        report($exception);
    }
}
