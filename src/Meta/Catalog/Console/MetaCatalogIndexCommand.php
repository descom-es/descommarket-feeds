<?php

namespace DescomMarket\Feeds\Meta\Catalog\Console;


use Illuminate\Console\Command;

class MetaCatalogIndexCommand extends Command
{
    protected $signature = 'dm360:meta:catalog:sync';

    protected $description = 'Sync all enqueued products in Meta catalog';

    public function handle()
    {
        //
    }
}
