<?php

namespace DescomMarket\Feeds\Google\Index\Listeners;

use DescomMarket\Common\Events\Urls\UrlCreated;
use DescomMarket\Feeds\Google\Index\Services\IndexUrlService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class IndexProductInGoogleListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $delay = 60;
    public $tries = 10;

    public function __construct()
    {
        $this->delay = config('feeds-google.index.queue.delay', 60);
        $this->tries = config('feeds-google.index.queue.tries', 10);
    }

    public function handle(UrlCreated $event)
    {
        $command = new IndexUrlService();

        $command->run($event->url);
    }

    public function viaConnection(): string
    {
        return config('feeds-google.index.queue.connection', 'sync');
    }

    public function viaQueue(): string
    {
        return config('feeds-google.index.queue.name', 'google_index');
    }

    public function failed(UrlCreated $event, Throwable $exception): void
    {
        report($exception);
    }
}
