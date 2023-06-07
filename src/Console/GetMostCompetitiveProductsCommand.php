<?php

namespace DescomMarket\Feeds\Console;

use DescomMarket\Feeds\Google\Merchant\Services\Products\ProductCompetitivenessService;
use Illuminate\Console\Command;

class GetMostCompetitiveProductsCommand extends Command
{
    protected $signature = 'dm360:google:most-competitive';

    protected $description = 'Get most competitive products from Google';

    public function handle()
    {
        $this->info('Getting most competitive products from Google');

        $products = (new ProductCompetitivenessService())->list();

        $this->info('Products retrieved');
        dd($products);
    }
}
