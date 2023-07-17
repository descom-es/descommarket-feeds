<?php

namespace DescomMarket\Feeds\Console\GoogleMerchant;

use Illuminate\Console\Command;
use DescomMarket\Feeds\Google\Merchant\Services\Products\ProductsExpirationRenewService;

class ProductsExprirationRenewCommand extends Command
{
    protected $signature = 'dm360:google-merchant:products:expiration:renew {--min-expiration-days=14}';

    protected $description = 'Renew Google Merchant products expiration';

    public function handle()
    {
        $minExpirationDays = $this->option('min-expiration-days');

        $success = ProductsExpirationRenewService::run($minExpirationDays);

        if ($success) {
            $this->info('Renewing Google Merchant products expiration completed.');
        } else {
            $this->error('Renewing Google Merchant products expiration failed.');
        }
    }
}
