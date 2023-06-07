<?php

namespace DescomMarket\Feeds\Google\Merchant\Services\Products;

use DescomMarket\Feeds\Google\GoogleServiceBuilder;
use Google\Service\ShoppingContent\ReportRow;
use Google\Service\ShoppingContent\SearchRequest;

class ProductCompetitivenessService
{
    public function list($pageToken = null, $perPage = 1000): array
    {
        $service = GoogleServiceBuilder::googleMerchant();

        $searchRequest = new SearchRequest([
            'pageSize' => $perPage,
            'pageToken' => $pageToken,
            'query' => "
                SELECT
                  product_view.id, product_view.title, product_view.brand,
                  product_view.price_micros,
                  product_view.currency_code,
                  price_competitiveness.country_code,
                  price_competitiveness.benchmark_price_micros,
                  price_competitiveness.benchmark_price_currency_code
                FROM PriceCompetitivenessProductView"
        ]);

        $results = $service->reports->search(config('feeds-google.merchant.id'), $searchRequest)->getResults();

        dd($results);

        $resultsObjects = array_map(function (ReportRow $item) {
            return $item->toSimpleObject();
        }, $results);

        return $resultsObjects;
    }
}
