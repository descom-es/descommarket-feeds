<?php

namespace DescomMarket\Feeds\GoogleMerchant;

use Google\Client as GoogleClient;
use GuzzleHttp\Client as GuzzleHttpClient;

//Hacerla singleton???
class GoogleMerchantConnection
{
    protected GuzzleHttpClient $client;

    public function __construct()
    {
        $enabled = config('google-merchant.enabled');
        $credentials = config('google-merchant.api.credentials.path');

        if (! $enabled || ! $credentials) {
            throw new \Exception('Google Merchant is not enabled or credentials are not set');
        }

        $this->client = $this->initClient($credentials);
    }

    private function initClient($credentials): GuzzleHttpClient
    {
        $client = new GoogleClient();

        $client->setApplicationName(config('google-merchant.api.app_name'));
        $client->setHttpClient(new \GuzzleHttp\Client($this->getClientConfig()));

        $client->setAuthConfig($credentials);
        $client->addScope('https://www.googleapis.com/auth/content');

        return $client->authorize();
    }

    private function getClientConfig(): array
    {
        return [
            'base_uri' => config('google-merchant.api.base_uri') . '/' . config('google-merchant.api.version') . '/' . config('google-merchant.api.id') . '/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ];
    }

    public function getClient(): GuzzleHttpClient
    {
        return $this->client;
    }
}
