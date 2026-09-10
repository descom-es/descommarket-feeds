<?php

namespace DescomMarket\Feeds\Google\Merchant;

use DescomMarket\Feeds\Google\Merchant\Services\DataSources\DataSourceService;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Shopping\Merchant\DataSources\V1\Client\DataSourcesServiceClient;
use Google\Shopping\Merchant\Products\V1\Client\ProductInputsServiceClient;

final class MerchantClientBuilder
{
    private const SCOPE = 'https://www.googleapis.com/auth/content';

    private static ?ProductInputsServiceClient $productInputs = null;

    private static ?DataSourcesServiceClient $dataSources = null;

    private static ?string $dataSource = null;

    public static function productInputs(): ProductInputsServiceClient
    {
        return self::$productInputs ??= new ProductInputsServiceClient(self::options());
    }

    public static function dataSources(): DataSourcesServiceClient
    {
        return self::$dataSources ??= new DataSourcesServiceClient(self::options());
    }

    public static function account(): string
    {
        $merchantId = config('feeds-google.merchant.id');

        if (! $merchantId) {
            throw new \Exception('No merchant id configured');
        }

        return "accounts/{$merchantId}";
    }

    public static function dataSource(): string
    {
        if (self::$dataSource) {
            return self::$dataSource;
        }

        $configured = config('feeds-google.merchant.data_source');

        if ($configured) {
            return self::$dataSource = self::account()."/dataSources/{$configured}";
        }

        return self::$dataSource = DataSourceService::resolve()->getName();
    }

    public static function productInputName(int|string $offerId): string
    {
        $contentLanguage = config('feeds-google.merchant.content_language', 'es');
        $feedLabel = config('feeds-google.merchant.feed_label', 'ES');

        return self::account()."/productInputs/{$contentLanguage}~{$feedLabel}~{$offerId}";
    }

    public static function credentials(): ServiceAccountCredentials
    {
        $credentials = config('feeds-google.api.credentials.path');

        if (! $credentials) {
            throw new \Exception('No can connect to Google without credentials json file');
        }

        return new ServiceAccountCredentials(self::SCOPE, $credentials);
    }

    private static function options(): array
    {
        return [
            'credentials' => self::credentials(),
            'transport' => config('feeds-google.merchant.transport', 'rest'),
        ];
    }
}
