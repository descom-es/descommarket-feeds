<?php

namespace DescomMarket\Feeds\Google\Index\Services;

use DescomMarket\Feeds\Google\Models\UrlIndexingQueueModel;

class EnqueueUrlService
{
    public static function publish(string $url,  int $priority = 20)
    {
        $enabled = config('feeds-google.index.enabled');

        if (! $enabled) {
            return;
        }

        $urlIndexing = UrlIndexingQueueModel::where('url', $url)->first();

        if (! $urlIndexing) {

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

    public static function unpublish(string $url,  int $priority = 99)
    {
        $enabled = config('feeds-google.index.enabled');

        if (! $enabled) {
            return;
        }

        $urlIndexing = UrlIndexingQueueModel::where('url', $url)->first();


        if (! $urlIndexing) {
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
