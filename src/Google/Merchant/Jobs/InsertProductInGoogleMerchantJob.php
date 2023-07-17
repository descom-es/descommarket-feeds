<?php

namespace DescomMarket\Feeds\Google\Merchant\Jobs;

use DescomMarket\Common\Repositories\Catalog\Products\ProductRepository;
use DescomMarket\Feeds\Google\Merchant\Services\Products\ProductInsertService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InsertProductInGoogleMerchantJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $delay;
    public $tries;

    public function __construct(private int $productId)
    {
        $this->delay = config('feeds-google.merchant.queue.delay', 60);
        $this->tries = config('feeds-google.merchant.queue.tries', 10);
        $this->queue = config('feeds-google.merchant.queue.name', 'google_merchant');
        $this->connection = config('feeds-google.merchant.queue.connection', 'sync');
    }

    public function handle()
    {
        $product = ProductRepository::get($this->productId);

        if (! $product) {
            return;
        }

        $command = new ProductInsertService();

        $command->run($product);
    }
}
