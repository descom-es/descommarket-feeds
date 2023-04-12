<?php

namespace DescomMarket\Feeds\GoogleMerchant\Listenters;

use DescomMarket\Common\Events\Catalog\Products\ProductPublished;
use DescomMarket\Common\Repositories\Catalog\Products\ProductRepository;
use DescomMarket\Feeds\GoogleMerchant\Services\Products\ProductsCreateService;
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
        if (!config('google-merchant.enabled')) {
            return;
        }

        $service = new ProductsInsertService();

        $service(ProductRepository::get($event->productId));
    }

    public function viaConnection(): string
    {
        return config('google-merchant.queue.connection');
    }

    public function viaQueue(): string
    {
        return config('google-merchant.queue.name');
    }

    public function failed(ProductPublished $event, Throwable $exception): void
    {
        report($exception);
    }
}
