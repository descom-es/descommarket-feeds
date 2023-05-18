<?php

namespace DescomMarket\Feeds\Google;

use Google\Client;
use Google\Service\Indexing;
use Google\Service\ShoppingContent;

final class GoogleServiceBuilder
{
    private static ?Client $client = null;

    public static function googleMerchant(): ShoppingContent
    {
        return self::service(ShoppingContent::class, ShoppingContent::CONTENT);
    }

    public static function googleIndex(): INDEXING
    {
        return self::service(Indexing::class, Indexing::INDEXING);
    }

    private static function service(string $serviceClassName, string|array $scopes)
    {
        $credentials = config('feeds-google.api.credentials.path');

        if (! $credentials) {
            throw new \Exception('No can connect to Google without credentials json file');
        }

        if (!self::$client) {
            self::$client = new Client();

            self::$client->setAuthConfig($credentials);

            self::$client->addScope($scopes);

        }


        return new $serviceClassName(self::$client);
    }
}
