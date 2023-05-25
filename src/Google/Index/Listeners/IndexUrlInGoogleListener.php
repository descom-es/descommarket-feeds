<?php

namespace DescomMarket\Feeds\Google\Index\Listeners;

use DescomMarket\Common\Events\Urls\UrlCreated;
use DescomMarket\Feeds\Google\Index\Services\EnqueueUrlService;
use DescomMarket\Feeds\Google\Index\Services\IndexUrlService;

class IndexProductInGoogleListener
{
    public function handle(UrlCreated $event)
    {
        EnqueueUrlService::index($event->url);
    }
}
