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

        $search = $service->reports->search(config('feeds-google.merchant.id'), $searchRequest);

        $nextPageToken = $search->getNextPageToken();
        $results = $search->getResults();

        $resultsAsObjects = collect($results)->map(function (ReportRow $item) {
            return [
                'productView' => $item->getProductView(),
                'priceCompetitiveness' => $item->getPriceCompetitiveness()
            ];
        })->toArray();

        return [
            'results' => $resultsAsObjects,
            'nextPageToken' => $nextPageToken
        ];
    }

    public function get($productId)
    {
        $service = GoogleServiceBuilder::googleMerchant();

        $searchRequest = new SearchRequest([
            'pageSize' => 1,
            'query' => "
                SELECT
                    product_view.id,
                    product_view.title,
                    product_view.brand,
                    product_view.price_micros,
                    product_view.currency_code,
                    price_competitiveness.country_code,
                    price_competitiveness.benchmark_price_micros,
                    price_competitiveness.benchmark_price_currency_code
                FROM PriceCompetitivenessProductView
                WHERE product_view.id LIKE 'online:es:ES:$productId'"
        ]);

        $search = $service->reports->search(config('feeds-google.merchant.id'), $searchRequest);

        $results = $search->getResults();

        $resultsAsObjects = collect($results)->map(function (ReportRow $item) {
            return [
                'productView' => $item->getProductView(),
                'priceCompetitiveness' => $item->getPriceCompetitiveness()
            ];
        })->first();

        return $resultsAsObjects;
    }
}
