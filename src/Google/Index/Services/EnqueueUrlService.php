<?php

namespace DescomMarket\Feeds\Google\Index\Services;

use DescomMarket\Feeds\Google\Models\UrlIndexingQueueModel;
use Google\Service\Indexing\UrlNotification;

class EnqueueUrlService
{
    public static function index(string $url,  int $priority = 10)
    {
        $enabled = config('feeds-google.index.enabled');

        if (!$enabled) {

            return;
        }

        $urlIndexing = UrlIndexingQueueModel::where('url', $url)->first();

        if (!$urlIndexing) {

            UrlIndexingQueueModel::create([
                'url' => $url,
                'action' => 'index',
                'priority' => $priority,
            ]);

            return;
        }

        if ($urlIndexing->action === 'unindex') {
            $urlIndexing->delete();
        }
    }

    public static function unindex(string $url,  int $priority = 20)
    {
        $enabled = config('feeds-google.index.enabled');

        if (!$enabled) {
            return;
        }

        $urlIndexing = UrlIndexingQueueModel::where('url', $url)->first();


        if (!$urlIndexing) {
            UrlIndexingQueueModel::create([
                'url' => $url,
                'action' => 'unindex',
                'priority' => $priority,
            ]);

            return;
        }

        if ($urlIndexing->action === 'index') {
            $urlIndexing->delete();
        }
    }
}
