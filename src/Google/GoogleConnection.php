<?php

namespace DescomMarket\Feeds\Google;

use Google\Client as GoogleClient;
use GuzzleHttp\Client as GuzzleHttpClient;

class GoogleConnection
{
    protected GuzzleHttpClient $client;

    public function __construct()
    {
        $credentials = config('feeds-google.api.credentials.path');

        if (! $credentials) {
            throw new \Exception('No can connect to Google without credentials json file');
        }

        $this->client = $this->initClient($credentials);
    }

    private function initClient($credentials)
    {
        $client = new GoogleClient();

        $client->setApplicationName(config('feeds-google.api.app_name'));
        $client->setHttpClient(new \GuzzleHttp\Client($this->getClientConfig()));

        $client->setAuthConfig($credentials);
        $client->addScope('https://www.googleapis.com/auth/content');

        return $client->authorize();
    }

    private function getClientConfig(): array
    {
        return [
            'base_uri' => config('feeds-google.api.base_uri') . '/' . config('feeds-google.api.version') . '/' . config('feeds-google.api.id') . '/',
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
