<?php

namespace DescomMarket\Feeds\Google\Index\Listeners;

use DescomMarket\Common\Events\Urls\UrlCreated;
use DescomMarket\Feeds\Google\Index\Services\IndexUrlService;
use DescomMarket\Feeds\Google\UrlStore;

class IndexProductInGoogleListener
{

    public function handle(UrlCreated $event)
    {
        $storeUrl = new UrlStore();
        $storeUrl->index($event->url);
    }
}
