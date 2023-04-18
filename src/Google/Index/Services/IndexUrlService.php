<?php

namespace DescomMarket\Feeds\Google\Index\Services;

use DescomMarket\Feeds\Google\GoogleServiceBuilder;
use Google\Service\Indexing\UrlNotification;

class IndexUrlService
{
    public function run(string $url)
    {
        $enabled = config('feeds-google.index.enabled');

        if (! $enabled) {
            return;
        }

        $urlNotification = new UrlNotification();
        $urlNotification->setType('URL_UPDATED');
        $urlNotification->setUrl($url);

        return GoogleServiceBuilder::googleIndex()->urlNotifications->publish($urlNotification);
    }
}
