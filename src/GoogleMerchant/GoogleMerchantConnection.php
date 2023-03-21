<?php

namespace Descom\B2b\Core\App\Feeds\GoogleMerchant;

use GuzzleHttp\Client as GuzzleHttpClient;
use Google\Client as GoogleClient;

//Hacerla singleton???
class GoogleMerchantConnection
{
    protected GuzzleHttpClient $client;

    public function __construct()
    {
        $enabled = config('google-merchant.enabled');
        $credentials = config('google-merchant.credentials.path');

        if (!$enabled || !$credentials) {
            throw new \Exception('Google Merchant is not enabled or credentials are not set');
        }

        $this->client = $this->initClient($credentials);
    }

    private function initClient($credentials): GuzzleHttpClient
    {
        $client = new GoogleClient();

        $client->setApplicationName(config('google-merchant.app_name'));
        $client->setHttpClient(new GuzzleHttpClient($this->getClientConfig()));

        $client->setAuthConfig($credentials);
        $client->addScope('https://www.googleapis.com/auth/content');

        return $client->authorize();
    }

    private function getClientConfig(): array
    {
        return [
            'base_uri' => config('google-merchant.base_uri') . '/' . config('google-merchant.version') . '/' . config('google-merchant.id') . '/',
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
