<?php

namespace DescomMarket\Feeds\Google\Merchant\Listenters;

use DescomMarket\Common\Events\Catalog\Products\ProductUnpublished;
use DescomMarket\Common\Repositories\Catalog\Products\ProductRepository;
use DescomMarket\Feeds\Google\Merchant\Services\Products\ProductDeleteService;
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
        $this->delay = config('feeds-google.merchant.queue.delay');
        $this->tries = config('feeds-google.merchant.queue.tries');
    }

    public function handle(ProductUnpublished $event)
    {
        if (ProductRepository::get($event->productId)) {
            return;
        }

        $command = new ProductDeleteService();

        $command->run($event->productId);
    }

    public function viaConnection(): string
    {
        return config('feeds-google.merchant.queue.connection', 'sync');
    }

    public function viaQueue(): string
    {
        return config('feeds-google.merchant.queue.name', 'google_merchant');
    }

    public function failed(ProductUnpublished $event, Throwable $exception): void
    {
        report($exception);
    }
}
