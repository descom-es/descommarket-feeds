<?php

namespace DescomMarket\Feeds\Google\Merchant\Services\Metrics;

use DescomMarket\Feeds\Google\GoogleServiceBuilder;
use Google\Service\ShoppingContent\ReportRow;
use Google\Service\ShoppingContent\SearchRequest;

class PerformanceViewService
{
    public function get($startDate, $endDate, $pageToken = null, $perPage = 1000)
    {
        $service = GoogleServiceBuilder::googleMerchant();

        $searchRequest = new SearchRequest([
            'pageSize' => $perPage,
            'pageToken' => $pageToken,
            'query' => "
                SELECT
                    segments.date,
                    segments.week,
                    segments.program,
                    segments.offer_id,
                    segments.title,
                    segments.brand,
                    segments.currency_code,
                    metrics.clicks,
                    metrics.impressions,
                    metrics.ctr,
                    metrics.conversions,
                    metrics.conversion_value_micros,
                    metrics.conversion_rate,
                    metrics.item_fill_rate
                FROM MerchantPerformanceView
                WHERE segments.date BETWEEN '$startDate' AND '$endDate'"
        ]);

        $search = $service->reports->search(config('feeds-google.merchant.id'), $searchRequest);

        $nextPageToken = $search->getNextPageToken();
        $results = $search->getResults();

        return [
            'results' => $results,
            'nextPageToken' => $nextPageToken
        ];
    }
}
