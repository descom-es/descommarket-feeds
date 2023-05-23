<?php

namespace DescomMarket\Feeds\Google\Index\Listeners;

use DescomMarket\Common\Events\Urls\UrlDeleted;
use DescomMarket\Feeds\Google\Index\Services\UnIndexUrlService;
use DescomMarket\Feeds\Google\UrlStore;

class UnIndexProductInGoogleListener
{

    public function handle(UrlDeleted $event)
    {
        $storeUrl = new UrlStore();
        $storeUrl->unindex($event->url);
    }
}
