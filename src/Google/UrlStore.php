<?php

namespace DescomMarket\Feeds\Google;

use DescomMarket\Feeds\Google\Models\Indexer;
use Google\Service\Indexing\UrlNotification;

class UrlStore
{

    public function index(string $url,  int $priority = 10)
    {
        $enabled = config('feeds-google.index.enabled');

        if (!$enabled) {

            return;
        }

        $existUrl = Indexer::where('url', $url)->first();

        if (!$existUrl) {

            Indexer::create([
                'url' => $url,
                'action' => 'index',
                'priority' => $priority,
            ]);

            return;
        }

        if ($existUrl->action === 'unindex') {
            $existUrl->delete();
        }
    }

    public function unindex(string $url,  int $priority = 20)
    {
        $enabled = config('feeds-google.index.enabled');

        if (!$enabled) {
            return;
        }

        $existUrl = Indexer::where('url', $url)->first();


        if (!$existUrl) {
            Indexer::create([
                'url' => $url,
                'action' => 'unindex',
                'priority' => $priority,
            ]);

            return;
        }

        if ($existUrl->action === 'index') {
            $existUrl->delete();
        }
    }
}
