<?php

namespace DescomMarket\Feeds\Google\Merchant\Services\DataSources;

use DescomMarket\Feeds\Google\Merchant\MerchantClientBuilder;
use Google\Shopping\Merchant\DataSources\V1\CreateDataSourceRequest;
use Google\Shopping\Merchant\DataSources\V1\DataSource;
use Google\Shopping\Merchant\DataSources\V1\ListDataSourcesRequest;
use Google\Shopping\Merchant\DataSources\V1\PrimaryProductDataSource;

final class DataSourceService
{
    /**
     * @return DataSource[]
     */
    public static function list(): array
    {
        $dataSources = MerchantClientBuilder::dataSources()
            ->listDataSources(ListDataSourcesRequest::build(MerchantClientBuilder::account()));

        return iterator_to_array($dataSources->iterateAllElements(), false);
    }

    /**
     * La fuente donde enviamos los productos, identificada por su nombre para
     * no confundirla con otras que tenga la cuenta. Si no existe se crea.
     */
    public static function resolve(): DataSource
    {
        $displayName = self::displayName();

        $matches = array_values(array_filter(
            self::list(),
            fn (DataSource $dataSource) => $dataSource->getPrimaryProductDataSource()
                && $dataSource->getDisplayName() === $displayName
        ));

        if (count($matches) > 1) {
            throw new \Exception("La cuenta tiene varias fuentes principales llamadas \"{$displayName}\", no se puede elegir");
        }

        return $matches[0] ?? self::create($displayName);
    }

    public static function displayName(): string
    {
        $displayName = config('feeds-google.merchant.data_source_name');

        if (! $displayName) {
            throw new \Exception('No data source name configured, set GOOGLE_MERCHANT_DATA_SOURCE_NAME');
        }

        return $displayName;
    }

    public static function create(string $displayName): DataSource
    {
        $primary = new PrimaryProductDataSource();

        $primary->setFeedLabel(config('feeds-google.merchant.feed_label', 'ES'));
        $primary->setContentLanguage(config('feeds-google.merchant.content_language', 'es'));

        $dataSource = new DataSource();

        $dataSource->setDisplayName($displayName);
        $dataSource->setPrimaryProductDataSource($primary);

        return MerchantClientBuilder::dataSources()->createDataSource(
            CreateDataSourceRequest::build(MerchantClientBuilder::account(), $dataSource)
        );
    }
}
