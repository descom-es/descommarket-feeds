<?php

namespace DescomMarket\Feeds\Google\Index\Listeners;

use DescomMarket\Common\Events\Urls\UrlDeleted;
use DescomMarket\Feeds\Google\Index\Services\EnqueueUrlService;
use DescomMarket\Feeds\Google\Index\Services\UnIndexUrlService;

class UnIndexProductInGoogleListener
{

    public function handle(UrlDeleted $event)
    {
        EnqueueUrlService::unindex($event->url);
    }
}
