<?php

namespace DescomMarket\Feeds\Google;

use Google\Client;
use Google\Service\ShoppingContent;

final class GoogleServiceBuilder
{
    public static function googleMerchant(): ShoppingContent
    {
        return self::service(ShoppingContent::class, ShoppingContent::CONTENT);
    }

    private static function service(string $serviceClassName, string|array $scopes)
    {
        $credentials = config('feeds-google.api.credentials.path');

        if (! $credentials) {
            throw new \Exception('No can connect to Google without credentials json file');
        }

        $client = new Client();

        $client->setAuthConfig($credentials);

        $client->addScope($scopes);

        return new $serviceClassName($client);
    }
}
